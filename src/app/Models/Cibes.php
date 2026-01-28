<?php
/**
 * Cibes Model
 * Cibes 品牌模型
 */

namespace App\Models;

use Core\Model;

class Cibes extends Model
{
    protected $table = 'cibes';
    protected $primaryKey = 'id';
    
    /**
     * 取得所有品牌（含分頁）
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
        
        // 關鍵字搜尋
        if (!empty($filters['search'])) {
            $where[] = '(name LIKE :search OR content LIKE :search)';
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
        
        // 查詢資料
        $sql = "SELECT * FROM {$this->table} {$whereClause} ORDER BY sort_order ASC, id DESC LIMIT {$perPage} OFFSET {$offset}";
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
     * 取得所有品牌（不分頁）
     */
    public function getAll(bool $activeOnly = false): array
    {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];
        
        if ($activeOnly) {
            $sql .= " WHERE status = 1";
        }
        
        $sql .= " ORDER BY sort_order ASC, id DESC";
        
        return $this->db->query($sql, $params)->fetchAll();
    }
    
    /**
     * 依類別取得品牌
     */
    public function getByCategory(string $category, bool $activeOnly = true): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE category = :category";
        $params = ['category' => $category];
        
        if ($activeOnly) {
            $sql .= " AND status = 1";
        }
        
        $sql .= " ORDER BY sort_order ASC, id DESC";
        
        return $this->db->query($sql, $params)->fetchAll();
    }
    
    /**
     * 取得前台顯示的品牌
     */
    public function getActiveCibes(int $limit = 20): array
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE status = 1 
                ORDER BY sort_order ASC, id DESC 
                LIMIT :limit";
        
        return $this->db->query($sql, ['limit' => $limit])->fetchAll();
    }
    
    /**
     * 建立品牌
     */
    public function create(array $data)
    {
        $sql = "INSERT INTO {$this->table} 
                (name, category, content, image_path, sort_order, status) 
                VALUES 
                (:name, :category, :content, :image_path, :sort_order, :status)";
        
        $params = [
            'name' => $data['name'],
            'category' => $data['category'] ?? null,
            'content' => $data['content'] ?? null,
            'image_path' => $data['image_path'] ?? null,
            'sort_order' => $data['sort_order'] ?? $this->getNextSortOrder(),
            'status' => $data['status'] ?? 1
        ];
        
        $result = $this->db->query($sql, $params);
        return $result ? $this->db->lastInsertId() : false;
    }
    
    /**
     * 更新品牌
     */
    public function update($id, array $data)
    {
        $fields = [];
        $params = ['id' => $id];
        
        $allowedFields = ['name', 'category', 'content', 'image_path', 'sort_order', 'status'];
        
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
     * 刪除品牌
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
        $uploadDir = ROOT_PATH . '/public/uploads/cibes';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // 生成檔名
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'cibes_' . date('YmdHis') . '_' . uniqid() . '.' . $extension;
        $filepath = $uploadDir . '/' . $filename;
        
        // 移動檔案
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return '/uploads/cibes/' . $filename;
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
