<?php
namespace App\Models;

use Core\Model;

class ActionLog extends Model
{
    protected $table = 'actionlogs';
    protected $fillable = [
        'user_id',
        'username',
        'action',
        'controller',
        'method',
        'url',
        'http_method',
        'ip_address',
        'user_agent',
        'request_data',
        'response_code',
        'description'
    ];
    
    /**
     * 取得最近的操作紀錄
     */
    public function getRecent($limit = 10)
    {
        $sql = "SELECT * FROM {$this->table} 
                ORDER BY created_at DESC 
                LIMIT :limit";
        
        return $this->db->query($sql, ['limit' => $limit])->fetchAll();
    }
    
    /**
     * 搜尋操作紀錄
     */
    public function search($filters = [], $page = 1, $perPage = 20)
    {
        $where = ['1=1'];
        $params = [];
        
        if (!empty($filters['user_id'])) {
            $where[] = 'user_id = :user_id';
            $params['user_id'] = $filters['user_id'];
        }
        
        if (!empty($filters['action'])) {
            $where[] = 'action = :action';
            $params['action'] = $filters['action'];
        }
        
        if (!empty($filters['controller'])) {
            $where[] = 'controller LIKE :controller';
            $params['controller'] = '%' . $filters['controller'] . '%';
        }
        
        if (!empty($filters['start_date'])) {
            $where[] = 'created_at >= :start_date';
            $params['start_date'] = $filters['start_date'] . ' 00:00:00';
        }
        
        if (!empty($filters['end_date'])) {
            $where[] = 'created_at <= :end_date';
            $params['end_date'] = $filters['end_date'] . ' 23:59:59';
        }
        
        if (!empty($filters['ip_address'])) {
            $where[] = 'ip_address = :ip_address';
            $params['ip_address'] = $filters['ip_address'];
        }
        
        $whereClause = implode(' AND ', $where);
        $offset = ($page - 1) * $perPage;
        
        // 計算總數
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} WHERE {$whereClause}";
        $total = $this->db->query($countSql, $params)->fetch()['total'];
        
        // 查詢資料
        $sql = "SELECT * FROM {$this->table} 
                WHERE {$whereClause} 
                ORDER BY created_at DESC 
                LIMIT {$perPage} OFFSET {$offset}";
        
        $data = $this->db->query($sql, $params)->fetchAll();
        
        return [
            'data' => $data,
            'total' => (int) $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => max(1, ceil($total / $perPage))
        ];
    }
    
    /**
     * 取得操作類型選項
     */
    public static function getActionOptions()
    {
        return [
            'view' => '檢視',
            'create' => '新增',
            'update' => '修改',
            'delete' => '刪除',
            'other' => '其他'
        ];
    }
    
    /**
     * 分頁查詢 (含使用者資訊)
     */
    public function paginateWithUser($page = 1, $perPage = 20, $conditions = [], $params = [])
    {
        $page = max(1, (int) $page);
        $offset = ($page - 1) * $perPage;
        
        $whereClause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';
        
        // 計算總數
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} {$whereClause}";
        $total = $this->db->query($countSql, $params)->fetch()['total'];
        
        // 查詢資料 (含使用者資訊)
        $sql = "SELECT al.*, u.username, u.display_name 
                FROM {$this->table} al
                LEFT JOIN acusers u ON al.user_id = u.id
                {$whereClause}
                ORDER BY al.created_at DESC
                LIMIT {$perPage} OFFSET {$offset}";
        
        $data = $this->db->query($sql, $params)->fetchAll();
        
        return [
            'data' => $data,
            'pagination' => [
                'total' => (int) $total,
                'per_page' => $perPage,
                'current_page' => $page,
                'total_pages' => max(1, ceil($total / $perPage))
            ]
        ];
    }
    
    /**
     * 依 ID 查詢 (含使用者資訊)
     */
    public function findWithUser($id)
    {
        $sql = "SELECT al.*, u.username, u.display_name 
                FROM {$this->table} al
                LEFT JOIN acusers u ON al.user_id = u.id
                WHERE al.id = :id
                LIMIT 1";
        
        return $this->db->query($sql, ['id' => $id])->fetch();
    }
    
    /**
     * 取得紀錄 (含使用者資訊)
     */
    public function getWithUser($conditions = [], $params = [], $limit = 1000)
    {
        $whereClause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';
        
        $sql = "SELECT al.*, u.username, u.display_name 
                FROM {$this->table} al
                LEFT JOIN acusers u ON al.user_id = u.id
                {$whereClause}
                ORDER BY al.created_at DESC
                LIMIT {$limit}";
        
        return $this->db->query($sql, $params)->fetchAll();
    }
    
    /**
     * 刪除指定日期之前的紀錄
     */
    public function deleteOlderThan($cutoffDate)
    {
        $sql = "DELETE FROM {$this->table} WHERE created_at < :cutoff_date";
        return $this->db->query($sql, ['cutoff_date' => $cutoffDate])->rowCount();
    }
}
