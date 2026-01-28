<?php
namespace App\Models;

use Core\Model;

class Role extends Model
{
    protected $table = 'acroles';
    protected $fillable = [
        'role_code',
        'role_name',
        'level',
        'description',
        'can_assign_to',
        'status'
    ];
    
    /**
     * 依角色代碼查詢
     */
    public function findByCode($code)
    {
        return $this->findBy('role_code', $code);
    }
    
    /**
     * 取得角色的所有功能權限
     */
    public function getFunctions($roleId)
    {
        $sql = "SELECT f.*, rf.can_view, rf.can_create, rf.can_edit, rf.can_delete
                FROM AcFunctions f
                INNER JOIN AcRoleFunctions rf ON f.id = rf.function_id
                WHERE rf.role_id = :role_id AND f.status = 1
                ORDER BY f.sort_order";
        
        return $this->db->query($sql, ['role_id' => $roleId])->fetchAll();
    }
    
    /**
     * 取得角色的功能 ID 陣列
     */
    public function getFunctionIds($roleId)
    {
        $sql = "SELECT function_id FROM AcRoleFunctions WHERE role_id = :role_id";
        $functions = $this->db->query($sql, ['role_id' => $roleId])->fetchAll();
        
        return array_column($functions, 'function_id');
    }
    
    /**
     * 取得角色的功能權限 (含詳細權限)
     */
    public function getFunctionPermissions($roleId)
    {
        $sql = "SELECT function_id, can_view, can_create, can_edit, can_delete
                FROM AcRoleFunctions 
                WHERE role_id = :role_id";
        
        $permissions = $this->db->query($sql, ['role_id' => $roleId])->fetchAll();
        
        $result = [];
        foreach ($permissions as $perm) {
            $result[$perm['function_id']] = [
                'can_view' => (bool) $perm['can_view'],
                'can_create' => (bool) $perm['can_create'],
                'can_edit' => (bool) $perm['can_edit'],
                'can_delete' => (bool) $perm['can_delete'],
            ];
        }
        
        return $result;
    }
    
    /**
     * 設定角色功能權限
     */
    public function setFunctions($roleId, array $functions)
    {
        $this->db->beginTransaction();
        
        try {
            // 先刪除現有權限
            $this->db->query(
                "DELETE FROM AcRoleFunctions WHERE role_id = :role_id",
                ['role_id' => $roleId]
            );
            
            // 新增權限
            foreach ($functions as $func) {
                $this->db->query(
                    "INSERT INTO AcRoleFunctions 
                     (role_id, function_id, can_view, can_create, can_edit, can_delete)
                     VALUES (:role_id, :function_id, :can_view, :can_create, :can_edit, :can_delete)",
                    [
                        'role_id' => $roleId,
                        'function_id' => $func['function_id'],
                        'can_view' => $func['can_view'] ?? 1,
                        'can_create' => $func['can_create'] ?? 0,
                        'can_edit' => $func['can_edit'] ?? 0,
                        'can_delete' => $func['can_delete'] ?? 0,
                    ]
                );
            }
            
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    /**
     * 取得可授權的角色
     */
    public function getAssignableRoles($currentLevel)
    {
        $sql = "SELECT * FROM AcRoles 
                WHERE level > :level AND status = 1
                ORDER BY level";
        
        return $this->db->query($sql, ['level' => $currentLevel])->fetchAll();
    }
    
    /**
     * 取得可用角色 (別名方法)
     */
    public function getAvailableRoles($currentLevel)
    {
        return $this->getAssignableRoles($currentLevel);
    }
    
    /**
     * 取得所有啟用的角色
     */
    public function getActiveRoles()
    {
        return $this->where('status', 1);
    }
    
    /**
     * 檢查是否可以授權特定角色
     */
    public function canAssignRole($assignerLevel, $roleId)
    {
        $role = $this->find($roleId);
        
        if (!$role) {
            return false;
        }
        
        // 只能授權比自己層級低的角色
        return $role['level'] > $assignerLevel;
    }
}
