<?php
namespace App\Models;

use Core\Model;

class FunctionModel extends Model
{
    protected $table = 'acfunctions';
    protected $fillable = [
        'function_code',
        'function_name',
        'parent_id',
        'url',
        'controller',
        'action',
        'icon',
        'sort_order',
        'is_menu',
        'min_level',
        'status'
    ];
    
    /**
     * 依功能代碼查詢
     */
    public function findByCode($code)
    {
        return $this->findBy('function_code', $code);
    }
    
    /**
     * 取得所有功能 (樹狀結構)
     */
    public function getTree()
    {
        $functions = $this->all(['*'], 'sort_order ASC');
        return $this->buildTree($functions);
    }
    
    /**
     * 建構樹狀結構
     */
    protected function buildTree(array $items, $parentId = null)
    {
        $tree = [];
        
        foreach ($items as $item) {
            if ($item['parent_id'] == $parentId) {
                $children = $this->buildTree($items, $item['id']);
                
                if ($children) {
                    $item['children'] = $children;
                }
                
                $tree[] = $item;
            }
        }
        
        return $tree;
    }
    
    /**
     * 取得選單項目
     */
    public function getMenuItems($userLevel = 3)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE status = 1 AND is_menu = 1 AND min_level >= :level
                ORDER BY sort_order";
        
        $functions = $this->db->query($sql, ['level' => $userLevel])->fetchAll();
        
        return $this->buildTree($functions);
    }
    
    /**
     * 取得使用者可存取的選單
     */
    public function getUserMenu($userId, $userLevel)
    {
        // 如果是 admin，顯示所有選單
        if ($userLevel == 1) {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE status = 1 AND is_menu = 1
                    ORDER BY sort_order";
            
            $functions = $this->db->query($sql)->fetchAll();
        } else {
            // 其他使用者只顯示有權限的選單
            $sql = "SELECT DISTINCT f.*
                    FROM {$this->table} f
                    INNER JOIN AcRoleFunctions rf ON f.id = rf.function_id
                    INNER JOIN AcUserRoles ur ON rf.role_id = ur.role_id
                    WHERE ur.user_id = :user_id 
                      AND f.status = 1 
                      AND f.is_menu = 1
                      AND rf.can_view = 1
                    ORDER BY f.sort_order";
            
            $functions = $this->db->query($sql, ['user_id' => $userId])->fetchAll();
            
            // 補上父層選單
            $parentIds = array_filter(array_unique(array_column($functions, 'parent_id')));
            
            if (!empty($parentIds)) {
                $placeholders = implode(',', array_fill(0, count($parentIds), '?'));
                $parentSql = "SELECT * FROM {$this->table} 
                              WHERE id IN ({$placeholders}) AND status = 1";
                
                $parents = $this->db->query($parentSql, $parentIds)->fetchAll();
                
                // 合併並去重
                $functionIds = array_column($functions, 'id');
                foreach ($parents as $parent) {
                    if (!in_array($parent['id'], $functionIds)) {
                        $functions[] = $parent;
                    }
                }
                
                // 重新排序
                usort($functions, function($a, $b) {
                    return $a['sort_order'] - $b['sort_order'];
                });
            }
        }
        
        return $this->buildTree($functions);
    }
    
    /**
     * 取得父選單列表 (用於下拉選單)
     */
    public function getParentOptions()
    {
        $sql = "SELECT id, function_name FROM {$this->table} 
                WHERE parent_id IS NULL AND status = 1
                ORDER BY sort_order";
        
        return $this->db->query($sql)->fetchAll();
    }
    
    /**
     * 取得所有啟用的功能
     */
    public function getActiveFunctions()
    {
        return $this->where('status', 1);
    }
}
