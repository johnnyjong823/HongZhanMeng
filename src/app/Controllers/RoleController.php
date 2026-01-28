<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\Role;
use App\Models\FunctionModel;
use App\Models\User;

class RoleController extends Controller
{
    protected $roleModel;
    protected $functionModel;
    protected $userModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->roleModel = new Role();
        $this->functionModel = new FunctionModel();
        $this->userModel = new User();
    }
    
    /**
     * 角色列表
     */
    public function index()
    {
        $currentLevel = $this->getCurrentUserLevel();
        $roles = $this->roleModel->getAvailableRoles($currentLevel);
        
        return $this->view('admin/roles/index', [
            'title' => '角色管理',
            'roles' => $roles
        ], 'admin');
    }
    
    /**
     * 新增角色頁面
     */
    public function create()
    {
        $currentLevel = $this->getCurrentUserLevel();
        $functions = $this->functionModel->getTree();
        
        // 取得可指派的權限等級
        $levels = $this->getAvailableLevels($currentLevel);
        
        return $this->view('admin/roles/create', [
            'title' => '新增角色',
            'functions' => $functions,
            'levels' => $levels
        ], 'admin');
    }
    
    /**
     * 儲存新角色
     */
    public function store()
    {
        if (!$this->verifyCsrf()) {
            return $this->json(['success' => false, 'message' => '安全驗證失敗']);
        }
        
        $name = trim($this->input('name', ''));
        $description = trim($this->input('description', ''));
        $level = (int)$this->input('level', 3);
        $functionIds = $this->input('functions', []);
        
        // 驗證
        if (empty($name)) {
            return $this->json(['success' => false, 'message' => '請輸入角色名稱']);
        }
        
        if ($this->roleModel->findByName($name)) {
            return $this->json(['success' => false, 'message' => '角色名稱已存在']);
        }
        
        // 驗證權限等級
        $currentLevel = $this->getCurrentUserLevel();
        if ($level <= $currentLevel) {
            return $this->json(['success' => false, 'message' => '您沒有權限建立此等級的角色']);
        }
        
        // 建立角色
        $roleId = $this->roleModel->create([
            'name' => $name,
            'description' => $description,
            'level' => $level,
            'status' => 1,
            'created_by' => $_SESSION['user']['id']
        ]);
        
        // 指派功能權限
        if (!empty($functionIds)) {
            $this->roleModel->assignFunctions($roleId, $functionIds);
        }
        
        return $this->json([
            'success' => true,
            'message' => '角色建立成功',
            'redirect' => '/admin/roles'
        ]);
    }
    
    /**
     * 編輯角色頁面
     */
    public function edit($id)
    {
        $role = $this->roleModel->find($id);
        
        if (!$role) {
            return $this->redirect('/admin/roles');
        }
        
        // 檢查權限
        $currentLevel = $this->getCurrentUserLevel();
        if ($role['level'] <= $currentLevel) {
            return $this->view('errors/403', ['message' => '您沒有權限編輯此角色']);
        }
        
        $functions = $this->functionModel->getTree();
        $roleFunctions = $this->roleModel->getFunctionIds($id);
        $levels = $this->getAvailableLevels($currentLevel);
        
        return $this->view('admin/roles/edit', [
            'title' => '編輯角色',
            'role' => $role,
            'functions' => $functions,
            'roleFunctions' => $roleFunctions,
            'levels' => $levels
        ], 'admin');
    }
    
    /**
     * 更新角色
     */
    public function update($id)
    {
        if (!$this->verifyCsrf()) {
            return $this->json(['success' => false, 'message' => '安全驗證失敗']);
        }
        
        $role = $this->roleModel->find($id);
        
        if (!$role) {
            return $this->json(['success' => false, 'message' => '角色不存在']);
        }
        
        // 檢查權限
        $currentLevel = $this->getCurrentUserLevel();
        if ($role['level'] <= $currentLevel) {
            return $this->json(['success' => false, 'message' => '您沒有權限編輯此角色']);
        }
        
        $name = trim($this->input('name', ''));
        $description = trim($this->input('description', ''));
        $level = (int)$this->input('level', 3);
        $functionIds = $this->input('functions', []);
        
        // 驗證
        if (empty($name)) {
            return $this->json(['success' => false, 'message' => '請輸入角色名稱']);
        }
        
        $existingRole = $this->roleModel->findByName($name);
        if ($existingRole && $existingRole['id'] != $id) {
            return $this->json(['success' => false, 'message' => '角色名稱已存在']);
        }
        
        // 驗證權限等級
        if ($level <= $currentLevel) {
            return $this->json(['success' => false, 'message' => '您沒有權限設定此等級']);
        }
        
        // 更新角色
        $this->roleModel->update($id, [
            'name' => $name,
            'description' => $description,
            'level' => $level,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        // 更新功能權限
        $this->roleModel->syncFunctions($id, $functionIds);
        
        return $this->json([
            'success' => true,
            'message' => '角色更新成功'
        ]);
    }
    
    /**
     * 刪除角色
     */
    public function delete($id)
    {
        if (!$this->verifyCsrf()) {
            return $this->json(['success' => false, 'message' => '安全驗證失敗']);
        }
        
        $role = $this->roleModel->find($id);
        
        if (!$role) {
            return $this->json(['success' => false, 'message' => '角色不存在']);
        }
        
        // 檢查權限
        $currentLevel = $this->getCurrentUserLevel();
        if ($role['level'] <= $currentLevel) {
            return $this->json(['success' => false, 'message' => '您沒有權限刪除此角色']);
        }
        
        // 檢查是否有使用者使用此角色
        $usersCount = $this->roleModel->getUsersCount($id);
        if ($usersCount > 0) {
            return $this->json(['success' => false, 'message' => "此角色尚有 {$usersCount} 位使用者，無法刪除"]);
        }
        
        // 刪除角色 (軟刪除)
        $this->roleModel->update($id, [
            'status' => 0,
            'deleted_at' => date('Y-m-d H:i:s')
        ]);
        
        return $this->json([
            'success' => true,
            'message' => '角色已刪除'
        ]);
    }
    
    /**
     * 取得目前使用者最高權限等級
     */
    protected function getCurrentUserLevel()
    {
        $userId = $_SESSION['user']['id'] ?? 0;
        return $this->userModel->getHighestLevel($userId);
    }
    
    /**
     * 取得可指派的權限等級
     */
    protected function getAvailableLevels($currentLevel)
    {
        $allLevels = [
            1 => '系統管理員 (最高權限)',
            2 => '營運管理員',
            3 => '一般使用者'
        ];
        
        return array_filter($allLevels, function($key) use ($currentLevel) {
            return $key > $currentLevel;
        }, ARRAY_FILTER_USE_KEY);
    }
}
