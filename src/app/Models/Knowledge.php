<?php
/**
 * Knowledge Model
 * 知識分享模型
 */

namespace App\Models;

use Core\Model;

class Knowledge extends Model
{
    protected $table = 'knowledge';
    protected $primaryKey = 'id';
    
    // 置頂數量上限
    const MAX_PINNED = 3;
    
    /**
     * 取得所有知識分享（含分頁）
     */
    public function search(array $filters = [], int $page = 1, int $perPage = 15): array
    {
        $where = [];
        $params = [];
        
        // 狀態篩選
        if (isset($filters['status']) && $filters['status'] !== '') {
            $where[] = 'status = :status';
            $params['status'] = (int)$filters['status'];
        }
        
        // 類別篩選
        if (!empty($filters['category'])) {
            $where[] = 'category = :category';
            $params['category'] = $filters['category'];
        }
        
        // 置頂篩選
        if (isset($filters['is_pinned']) && $filters['is_pinned'] !== '') {
            $where[] = 'is_pinned = :is_pinned';
            $params['is_pinned'] = (int)$filters['is_pinned'];
        }
        
        // 關鍵字搜尋
        if (!empty($filters['search'])) {
            $where[] = '(title LIKE :search OR content LIKE :search)';
            $params['search'] = '%' . $filters['search'] . '%';
        }
        
        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
        
        // 計算總數
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} {$whereClause}";
        $countResult = $this->db->query($countSql, $params)->fetch();
        $total = $countResult['total'];
        
        // 計算分頁
        $totalPages = ceil($total / $perPage);
        $offset = ($page - 1) * $perPage;
        
        // 查詢資料（置頂優先，再依排序、建立時間）
        $sql = "SELECT * FROM {$this->table} {$whereClause} 
                ORDER BY is_pinned DESC, sort_order ASC, created_at DESC 
                LIMIT {$perPage} OFFSET {$offset}";
        $items = $this->db->query($sql, $params)->fetchAll();
        
        return [
            'data' => $items,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'total_pages' => $totalPages
            ]
        ];
    }
    
    /**
     * 取得所有知識分享（不分頁）
     */
    public function getAll(bool $activeOnly = false): array
    {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];
        
        if ($activeOnly) {
            $sql .= " WHERE status = 1";
        }
        
        $sql .= " ORDER BY is_pinned DESC, sort_order ASC, created_at DESC";
        
        return $this->db->query($sql, $params)->fetchAll();
    }
    
    /**
     * 取得置頂的知識分享（前台首頁使用）
     */
    public function getPinned(int $limit = 3): array
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE status = 1 AND is_pinned = 1 
                ORDER BY sort_order ASC, created_at DESC 
                LIMIT :limit";
        
        return $this->db->query($sql, ['limit' => $limit])->fetchAll();
    }
    
    /**
     * 取得前台顯示的知識分享
     */
    public function getActiveKnowledge(int $limit = 20): array
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE status = 1 
                ORDER BY is_pinned DESC, sort_order ASC, created_at DESC 
                LIMIT :limit";
        
        return $this->db->query($sql, ['limit' => $limit])->fetchAll();
    }
    
    /**
     * 依類別取得知識分享
     */
    public function getByCategory(string $category, bool $activeOnly = true): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE category = :category";
        $params = ['category' => $category];
        
        if ($activeOnly) {
            $sql .= " AND status = 1";
        }
        
        $sql .= " ORDER BY is_pinned DESC, sort_order ASC, created_at DESC";
        
        return $this->db->query($sql, $params)->fetchAll();
    }
    
    /**
     * 計算目前置頂數量
     */
    public function countPinned(): int
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE is_pinned = 1";
        $result = $this->db->query($sql)->fetch();
        return (int)$result['count'];
    }
    
    /**
     * 檢查是否可以新增置頂
     */
    public function canAddPinned(?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE is_pinned = 1";
        $params = [];
        
        if ($excludeId !== null) {
            $sql .= " AND id != :id";
            $params['id'] = $excludeId;
        }
        
        $result = $this->db->query($sql, $params)->fetch();
        return (int)$result['count'] < self::MAX_PINNED;
    }
    
    /**
     * 建立知識分享
     */
    public function create(array $data)
    {
        $sql = "INSERT INTO {$this->table} 
                (title, category, content, image_path, is_pinned, sort_order, status) 
                VALUES 
                (:title, :category, :content, :image_path, :is_pinned, :sort_order, :status)";
        
        $params = [
            'title' => $data['title'],
            'category' => $data['category'],
            'content' => $data['content'] ?? null,
            'image_path' => $data['image_path'] ?? null,
            'is_pinned' => $data['is_pinned'] ?? 0,
            'sort_order' => $data['sort_order'] ?? $this->getNextSortOrder(),
            'status' => $data['status'] ?? 1
        ];
        
        $result = $this->db->query($sql, $params);
        return $result ? $this->db->lastInsertId() : false;
    }
    
    /**
     * 更新知識分享
     */
    public function update($id, array $data)
    {
        $fields = [];
        $params = ['id' => $id];
        
        $allowedFields = ['title', 'category', 'content', 'image_path', 'is_pinned', 'sort_order', 'status'];
        
        foreach ($allowedFields as $field) {
            if (array_key_exists($field, $data)) {
                $fields[] = "{$field} = :{$field}";
                $params[$field] = $data[$field];
            }
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
        return $this->db->query($sql, $params) !== false;
    }
    
    /**
     * 刪除知識分享
     */
    public function delete($id)
    {
        // 先取得圖片路徑
        $item = $this->find($id);
        if (!$item) {
            return false;
        }
        
        // 刪除實體檔案
        if (!empty($item['image_path'])) {
            $filePath = ROOT_PATH . '/public' . $item['image_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        // 刪除資料庫記錄
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->db->query($sql, ['id' => $id]) !== false;
    }
    
    /**
     * 批次更新排序
     */
    public function updateSortOrders(array $items): bool
    {
        foreach ($items as $item) {
            if (isset($item['id']) && isset($item['sort_order'])) {
                $this->update((int)$item['id'], ['sort_order' => (int)$item['sort_order']]);
            }
        }
        return true;
    }
    
    /**
     * 取得下一個排序數字
     */
    public function getNextSortOrder(): int
    {
        $sql = "SELECT MAX(sort_order) as max_order FROM {$this->table}";
        $result = $this->db->query($sql)->fetch();
        return ($result['max_order'] ?? 0) + 1;
    }
    
    /**
     * 切換狀態
     */
    public function toggleStatus($id)
    {
        $sql = "UPDATE {$this->table} SET status = IF(status = 1, 0, 1) WHERE id = :id";
        return $this->db->query($sql, ['id' => $id]) !== false;
    }
    
    /**
     * 切換置頂狀態
     */
    public function togglePinned($id)
    {
        $item = $this->find($id);
        if (!$item) {
            return ['success' => false, 'message' => '找不到指定的知識分享'];
        }
        
        // 如果目前未置頂，要設為置頂，需檢查置頂數量
        if ($item['is_pinned'] == 0 && !$this->canAddPinned()) {
            return ['success' => false, 'message' => '置頂數量已達上限（最多 ' . self::MAX_PINNED . ' 個）'];
        }
        
        $sql = "UPDATE {$this->table} SET is_pinned = IF(is_pinned = 1, 0, 1) WHERE id = :id";
        $result = $this->db->query($sql, ['id' => $id]) !== false;
        
        return ['success' => $result, 'message' => $result ? '置頂狀態已更新' : '更新失敗'];
    }
    
    /**
     * 上傳圖片
     */
    public function uploadImage(array $file)
    {
        // 驗證檔案
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        if (!in_array($file['type'], $allowedTypes)) {
            return false;
        }
        
        if ($file['size'] > $maxSize) {
            return false;
        }
        
        // 建立上傳目錄
        $uploadDir = ROOT_PATH . '/public/uploads/knowledge';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // 生成檔名
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'knowledge_' . date('YmdHis') . '_' . uniqid() . '.' . $extension;
        $filepath = $uploadDir . '/' . $filename;
        
        // 移動檔案
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return '/uploads/knowledge/' . $filename;
        }
        
        return false;
    }
    
    /**
     * 刪除圖片檔案
     */
    public function deleteImageFile($imagePath)
    {
        $filePath = ROOT_PATH . '/public' . $imagePath;
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return false;
    }
}
