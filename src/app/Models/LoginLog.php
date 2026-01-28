<?php
namespace App\Models;

use Core\Model;

class LoginLog extends Model
{
    protected $table = 'loginlogs';
    protected $fillable = [
        'user_id',
        'username',
        'ip_address',
        'user_agent',
        'login_status',
        'failure_reason',
        'session_id'
    ];
    
    /**
     * 記錄登入成功
     */
    public function recordSuccess($userId, $username)
    {
        return $this->create([
            'user_id' => $userId,
            'username' => $username,
            'ip_address' => get_client_ip(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'login_status' => 1,
            'session_id' => session_id()
        ]);
    }
    
    /**
     * 記錄登入失敗
     */
    public function recordFailure($username, $reason = null)
    {
        return $this->create([
            'user_id' => null,
            'username' => $username,
            'ip_address' => get_client_ip(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'login_status' => 0,
            'failure_reason' => $reason
        ]);
    }
    
    /**
     * 記錄登出
     */
    public function recordLogout($userId)
    {
        $sessionId = session_id();
        
        $sql = "UPDATE {$this->table} 
                SET logout_at = NOW() 
                WHERE user_id = :user_id 
                  AND session_id = :session_id 
                  AND logout_at IS NULL
                ORDER BY login_at DESC 
                LIMIT 1";
        
        return $this->db->query($sql, [
            'user_id' => $userId,
            'session_id' => $sessionId
        ]);
    }
    
    /**
     * 取得最近的失敗登入次數
     */
    public function getRecentFailures($username, $minutes = 15)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                WHERE username = :username 
                  AND login_status = 0 
                  AND login_at > DATE_SUB(NOW(), INTERVAL :minutes MINUTE)";
        
        $result = $this->db->query($sql, [
            'username' => $username,
            'minutes' => $minutes
        ])->fetch();
        
        return $result['count'];
    }
    
    /**
     * 檢查 IP 是否被暫時封鎖
     */
    public function isIpBlocked($ip, $maxAttempts = 10, $minutes = 30)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                WHERE ip_address = :ip 
                  AND login_status = 0 
                  AND login_at > DATE_SUB(NOW(), INTERVAL :minutes MINUTE)";
        
        $result = $this->db->query($sql, [
            'ip' => $ip,
            'minutes' => $minutes
        ])->fetch();
        
        return $result['count'] >= $maxAttempts;
    }
    
    /**
     * 計算今日登入次數
     */
    public function countTodayLogins()
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                WHERE login_status = 1 
                  AND DATE(login_at) = CURDATE()";
        
        $result = $this->db->query($sql)->fetch();
        return (int) $result['count'];
    }
    
    /**
     * 取得最近的登入紀錄
     */
    public function getRecent($limit = 10)
    {
        $sql = "SELECT * FROM {$this->table} 
                ORDER BY login_at DESC 
                LIMIT :limit";
        
        return $this->db->query($sql, ['limit' => $limit])->fetchAll();
    }
    
    /**
     * 搜尋登入紀錄
     */
    public function search($filters = [], $page = 1, $perPage = 50)
    {
        $where = ['1=1'];
        $params = [];
        
        if (!empty($filters['user_id'])) {
            $where[] = 'user_id = :user_id';
            $params['user_id'] = $filters['user_id'];
        }
        
        if (!empty($filters['username'])) {
            $where[] = 'username LIKE :username';
            $params['username'] = '%' . $filters['username'] . '%';
        }
        
        if (isset($filters['login_status']) && $filters['login_status'] !== '') {
            $where[] = 'login_status = :login_status';
            $params['login_status'] = $filters['login_status'];
        }
        
        if (!empty($filters['ip_address'])) {
            $where[] = 'ip_address = :ip_address';
            $params['ip_address'] = $filters['ip_address'];
        }
        
        if (!empty($filters['start_date'])) {
            $where[] = 'login_at >= :start_date';
            $params['start_date'] = $filters['start_date'] . ' 00:00:00';
        }
        
        if (!empty($filters['end_date'])) {
            $where[] = 'login_at <= :end_date';
            $params['end_date'] = $filters['end_date'] . ' 23:59:59';
        }
        
        $whereClause = implode(' AND ', $where);
        $offset = ($page - 1) * $perPage;
        
        // 計算總數
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} WHERE {$whereClause}";
        $total = $this->db->query($countSql, $params)->fetch()['total'];
        
        // 查詢資料
        $sql = "SELECT * FROM {$this->table} 
                WHERE {$whereClause} 
                ORDER BY login_at DESC 
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
     * 分頁查詢 (含使用者資訊)
     */
    public function paginateWithUser($page = 1, $perPage = 50, $conditions = [], $params = [])
    {
        $page = max(1, (int) $page);
        $offset = ($page - 1) * $perPage;
        
        $whereClause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';
        
        // 計算總數
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} {$whereClause}";
        $total = $this->db->query($countSql, $params)->fetch()['total'];
        
        // 查詢資料 (含使用者資訊)
        $sql = "SELECT ll.*, u.display_name 
                FROM {$this->table} ll
                LEFT JOIN AcUsers u ON ll.user_id = u.id
                {$whereClause}
                ORDER BY ll.login_at DESC
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
     * 取得統計資料
     */
    public function getStatistics($days = 30)
    {
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN login_status = 1 THEN 1 ELSE 0 END) as success_count,
                    SUM(CASE WHEN login_status = 0 THEN 1 ELSE 0 END) as failed_count
                FROM {$this->table}
                WHERE login_at >= DATE_SUB(NOW(), INTERVAL :days DAY)";
        
        return $this->db->query($sql, ['days' => $days])->fetch();
    }
    
    /**
     * 取得每日統計
     */
    public function getDailyStatistics($days = 30)
    {
        $sql = "SELECT 
                    DATE(login_at) as date,
                    SUM(CASE WHEN login_status = 1 THEN 1 ELSE 0 END) as success_count,
                    SUM(CASE WHEN login_status = 0 THEN 1 ELSE 0 END) as failed_count
                FROM {$this->table}
                WHERE login_at >= DATE_SUB(NOW(), INTERVAL :days DAY)
                GROUP BY DATE(login_at)
                ORDER BY date DESC";
        
        return $this->db->query($sql, ['days' => $days])->fetchAll();
    }
    
    /**
     * 取得登入次數最多的使用者
     */
    public function getTopUsers($days = 30, $limit = 10)
    {
        $sql = "SELECT username, COUNT(*) as login_count
                FROM {$this->table}
                WHERE login_status = 1 AND login_at >= DATE_SUB(NOW(), INTERVAL :days DAY)
                GROUP BY username
                ORDER BY login_count DESC
                LIMIT {$limit}";
        
        return $this->db->query($sql, ['days' => $days])->fetchAll();
    }
    
    /**
     * 取得失敗嘗試記錄
     */
    public function getFailedAttempts($days = 30, $limit = 20)
    {
        $sql = "SELECT username, ip_address, failure_reason, login_at
                FROM {$this->table}
                WHERE login_status = 0 AND login_at >= DATE_SUB(NOW(), INTERVAL :days DAY)
                ORDER BY login_at DESC
                LIMIT {$limit}";
        
        return $this->db->query($sql, ['days' => $days])->fetchAll();
    }
    
    /**
     * 取得紀錄 (含使用者資訊)
     */
    public function getWithUser($conditions = [], $params = [], $limit = 1000)
    {
        $whereClause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';
        
        $sql = "SELECT ll.*, u.display_name 
                FROM {$this->table} ll
                LEFT JOIN AcUsers u ON ll.user_id = u.id
                {$whereClause}
                ORDER BY ll.login_at DESC
                LIMIT {$limit}";
        
        return $this->db->query($sql, $params)->fetchAll();
    }
}
