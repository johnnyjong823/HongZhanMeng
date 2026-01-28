<?php
namespace App\Models;

use Core\Model;

class User extends Model
{
    protected $table = 'acusers';
    protected $fillable = [
        'username',
        'email',
        'password',
        'display_name',
        'phone',
        'avatar',
        'status',
        'email_verified_at',
        'password_changed_at',
        'remember_token',
        'reset_token',
        'reset_token_expires_at',
        'last_login_at',
        'last_login_ip',
        'login_attempts',
        'locked_until',
        'created_by'
    ];
    
    /**
     * 依帳號查詢
     */
    public function findByUsername($username)
    {
        return $this->findBy('username', $username);
    }
    
    /**
     * 依 Email 查詢
     */
    public function findByEmail($email)
    {
        return $this->findBy('email', $email);
    }
    
    /**
     * 取得使用者的所有角色
     */
    public function getRoles($userId)
    {
        $sql = "SELECT r.* FROM AcRoles r
                INNER JOIN AcUserRoles ur ON r.id = ur.role_id
                WHERE ur.user_id = :user_id AND r.status = 1
                ORDER BY r.level";
        
        return $this->db->query($sql, ['user_id' => $userId])->fetchAll();
    }
    
    /**
     * 取得使用者最高權限層級
     */
    public function getHighestLevel($userId)
    {
        $sql = "SELECT MIN(r.level) as level FROM AcRoles r
                INNER JOIN AcUserRoles ur ON r.id = ur.role_id
                WHERE ur.user_id = :user_id AND r.status = 1";
        
        $result = $this->db->query($sql, ['user_id' => $userId])->fetch();
        
        return $result['level'] ?? 999;
    }
    
    /**
     * 取得使用者所有功能權限
     */
    public function getPermissions($userId)
    {
        $sql = "SELECT 
                    f.function_code,
                    f.function_name,
                    f.url,
                    MAX(rf.can_view) as can_view,
                    MAX(rf.can_create) as can_create,
                    MAX(rf.can_edit) as can_edit,
                    MAX(rf.can_delete) as can_delete
                FROM AcFunctions f
                INNER JOIN AcRoleFunctions rf ON f.id = rf.function_id
                INNER JOIN AcUserRoles ur ON rf.role_id = ur.role_id
                WHERE ur.user_id = :user_id AND f.status = 1
                GROUP BY f.id, f.function_code, f.function_name, f.url";
        
        $permissions = $this->db->query($sql, ['user_id' => $userId])->fetchAll();
        
        // 轉換為以 function_code 為 key 的陣列
        $result = [];
        foreach ($permissions as $perm) {
            $result[$perm['function_code']] = [
                'function_name' => $perm['function_name'],
                'url' => $perm['url'],
                'can_view' => (bool) $perm['can_view'],
                'can_create' => (bool) $perm['can_create'],
                'can_edit' => (bool) $perm['can_edit'],
                'can_delete' => (bool) $perm['can_delete'],
            ];
        }
        
        return $result;
    }
    
    /**
     * 指派角色給使用者
     */
    public function assignRole($userId, $roleId, $assignedBy = null)
    {
        $sql = "INSERT IGNORE INTO AcUserRoles (user_id, role_id, assigned_by)
                VALUES (:user_id, :role_id, :assigned_by)";
        
        return $this->db->query($sql, [
            'user_id' => $userId,
            'role_id' => $roleId,
            'assigned_by' => $assignedBy
        ]);
    }
    
    /**
     * 移除使用者角色
     */
    public function removeRole($userId, $roleId)
    {
        $sql = "DELETE FROM AcUserRoles WHERE user_id = :user_id AND role_id = :role_id";
        return $this->db->query($sql, [
            'user_id' => $userId,
            'role_id' => $roleId
        ]);
    }
    
    /**
     * 設定使用者角色 (取代所有現有角色)
     */
    public function setRoles($userId, array $roleIds, $assignedBy = null)
    {
        $this->db->beginTransaction();
        
        try {
            // 刪除現有角色
            $this->db->query(
                "DELETE FROM AcUserRoles WHERE user_id = :user_id",
                ['user_id' => $userId]
            );
            
            // 指派新角色
            foreach ($roleIds as $roleId) {
                $this->assignRole($userId, $roleId, $assignedBy);
            }
            
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    /**
     * 取得使用者角色 ID 陣列
     */
    public function getRoleIds($userId)
    {
        $sql = "SELECT role_id FROM AcUserRoles WHERE user_id = :user_id";
        $roles = $this->db->query($sql, ['user_id' => $userId])->fetchAll();
        
        return array_column($roles, 'role_id');
    }
    
    /**
     * 分頁查詢 (含角色資訊)
     */
    public function paginateWithRoles($page = 1, $perPage = 20, $conditions = [], $orderBy = 'id DESC')
    {
        $result = $this->paginate($page, $perPage, $conditions, $orderBy);
        
        // 為每個使用者附加角色資訊
        foreach ($result['data'] as &$user) {
            $user['roles'] = $this->getRoles($user['id']);
        }
        
        return $result;
    }
    
    /**
     * 取得可管理的使用者 (依權限層級)
     */
    public function getManagedUsers($managerLevel, $page = 1, $perPage = 20)
    {
        $offset = ($page - 1) * $perPage;
        
        // 計算總數
        $countSql = "SELECT COUNT(DISTINCT u.id) as total
                     FROM AcUsers u
                     LEFT JOIN AcUserRoles ur ON u.id = ur.user_id
                     LEFT JOIN AcRoles r ON ur.role_id = r.id
                     GROUP BY u.id
                     HAVING MIN(COALESCE(r.level, 999)) > :level OR MIN(r.level) IS NULL";
        
        // 由於 MySQL 限制，我們用另一種方式
        $sql = "SELECT u.*, MIN(COALESCE(r.level, 999)) as user_level
                FROM AcUsers u
                LEFT JOIN AcUserRoles ur ON u.id = ur.user_id
                LEFT JOIN AcRoles r ON ur.role_id = r.id
                GROUP BY u.id
                HAVING user_level > :level
                ORDER BY u.created_at DESC
                LIMIT {$perPage} OFFSET {$offset}";
        
        $data = $this->db->query($sql, ['level' => $managerLevel])->fetchAll();
        
        // 附加角色資訊
        foreach ($data as &$user) {
            $user['roles'] = $this->getRoles($user['id']);
        }
        
        // 計算總數
        $totalSql = "SELECT COUNT(*) as count FROM (
                        SELECT u.id
                        FROM AcUsers u
                        LEFT JOIN AcUserRoles ur ON u.id = ur.user_id
                        LEFT JOIN AcRoles r ON ur.role_id = r.id
                        GROUP BY u.id
                        HAVING MIN(COALESCE(r.level, 999)) > :level
                     ) as subquery";
        
        $total = $this->db->query($totalSql, ['level' => $managerLevel])->fetch()['count'];
        
        return [
            'data' => $data,
            'total' => (int) $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => max(1, ceil($total / $perPage)),
        ];
    }
}
