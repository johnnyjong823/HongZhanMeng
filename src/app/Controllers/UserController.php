<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\User;
use App\Models\Role;
use App\Services\PasswordService;

class UserController extends Controller
{
    protected $userModel;
    protected $roleModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
        $this->roleModel = new Role();
    }
    
    /**
     * 使用者列表
     */
    public function index()
    {
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 20;
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';
        
        // 取得目前使用者權限等級
        $currentLevel = $this->getCurrentUserLevel();
        
        // 使用原生 SQL 查詢，支援複雜條件
        $whereClauses = ['1=1'];
        $params = [];
        
        if (!empty($search)) {
            $whereClauses[] = "(u.username LIKE :search OR u.display_name LIKE :search2 OR u.email LIKE :search3)";
            $params['search'] = "%{$search}%";
            $params['search2'] = "%{$search}%";
            $params['search3'] = "%{$search}%";
        }
        
        if ($status !== '') {
            $whereClauses[] = "u.status = :status";
            $params['status'] = (int)$status;
        }
        
        $whereClause = implode(' AND ', $whereClauses);
        $offset = ($page - 1) * $perPage;
        
        // 查詢使用者（根據權限等級過濾）
        $sql = "SELECT DISTINCT u.* FROM acusers u
                LEFT JOIN acuserroles ur ON u.id = ur.user_id
                LEFT JOIN acroles r ON ur.role_id = r.id
                WHERE {$whereClause}
                GROUP BY u.id
                HAVING MIN(COALESCE(r.level, 999)) > :currentLevel OR COUNT(r.id) = 0
                ORDER BY u.id DESC
                LIMIT {$perPage} OFFSET {$offset}";
        
        $params['currentLevel'] = $currentLevel;
        $users = $this->userModel->raw($sql, $params)->fetchAll();
        
        // 為每個使用者附加角色資訊
        foreach ($users as &$user) {
            $user['roles'] = $this->userModel->getRoles($user['id']);
        }
        
        // 計算總數
        $countSql = "SELECT COUNT(*) as total FROM (
                        SELECT u.id FROM acusers u
                        LEFT JOIN acuserroles ur ON u.id = ur.user_id
                        LEFT JOIN acroles r ON ur.role_id = r.id
                        WHERE {$whereClause}
                        GROUP BY u.id
                        HAVING MIN(COALESCE(r.level, 999)) > :currentLevel OR COUNT(r.id) = 0
                     ) as subquery";
        
        $total = $this->userModel->raw($countSql, $params)->fetch()['total'] ?? 0;
        
        $pagination = [
            'total' => (int)$total,
            'per_page' => $perPage,
            'current_page' => $page,
            'total_pages' => max(1, ceil($total / $perPage))
        ];
        
        $roles = $this->roleModel->getAvailableRoles($currentLevel);
        
        return $this->view('admin/users/index', [
            'title' => '使用者管理',
            'users' => $users,
            'pagination' => $pagination,
            'roles' => $roles,
            'search' => $search,
            'status' => $status
        ], 'admin');
    }
    
    /**
     * 新增使用者頁面
     */
    public function create()
    {
        $currentLevel = $this->getCurrentUserLevel();
        $roles = $this->roleModel->getAvailableRoles($currentLevel);
        
        return $this->view('admin/users/create', [
            'title' => '新增使用者',
            'roles' => $roles
        ], 'admin');
    }
    
    /**
     * 儲存新使用者
     */
    public function store()
    {
        if (!$this->verifyCsrf()) {
            return $this->json(['success' => false, 'message' => '安全驗證失敗']);
        }
        
        $username = trim($this->input('username', ''));
        $displayName = trim($this->input('display_name', ''));
        $email = trim($this->input('email', ''));
        $password = $this->input('password', '');
        $roleIds = $this->input('roles', []);
        $status = (int)$this->input('status', 1);
        
        // 驗證
        if (empty($username)) {
            return $this->json(['success' => false, 'message' => '請輸入帳號']);
        }
        
        if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
            return $this->json(['success' => false, 'message' => '帳號只能包含英文、數字和底線，長度 3-20 字元']);
        }
        
        if ($this->userModel->findByUsername($username)) {
            return $this->json(['success' => false, 'message' => '帳號已存在']);
        }
        
        if (empty($password)) {
            return $this->json(['success' => false, 'message' => '請輸入密碼']);
        }
        
        $passwordService = new PasswordService();
        $validation = $passwordService->validatePasswordStrength($password);
        if (!$validation['valid']) {
            return $this->json(['success' => false, 'message' => $validation['message']]);
        }
        
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->json(['success' => false, 'message' => 'Email 格式不正確']);
        }
        
        if (!empty($email) && $this->userModel->findByEmail($email)) {
            return $this->json(['success' => false, 'message' => 'Email 已被使用']);
        }
        
        // 驗證角色權限
        $currentLevel = $this->getCurrentUserLevel();
        foreach ($roleIds as $roleId) {
            $role = $this->roleModel->find($roleId);
            if (!$role || $role['level'] <= $currentLevel) {
                return $this->json(['success' => false, 'message' => '您沒有權限指派此角色']);
            }
        }
        
        // 建立使用者
        $userId = $this->userModel->create([
            'username' => $username,
            'display_name' => $displayName ?: $username,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]),
            'status' => $status,
            'created_by' => $_SESSION['user']['id']
        ]);
        
        // 指派角色
        if (!empty($roleIds)) {
            $this->userModel->assignRoles($userId, $roleIds);
        }
        
        return $this->json([
            'success' => true,
            'message' => '使用者建立成功',
            'redirect' => url('/admin/users')
        ]);
    }
    
    /**
     * 編輯使用者頁面
     */
    public function edit($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return $this->redirect('/admin/users');
        }
        
        // 檢查權限
        $currentLevel = $this->getCurrentUserLevel();
        $targetLevel = $this->userModel->getHighestLevel($id);
        
        if ($targetLevel <= $currentLevel && $id != $_SESSION['user']['id']) {
            return $this->view('errors/403', ['message' => '您沒有權限編輯此使用者']);
        }
        
        $roles = $this->roleModel->getAvailableRoles($currentLevel);
        $userRoles = $this->userModel->getRoleIds($id);
        
        return $this->view('admin/users/edit', [
            'title' => '編輯使用者',
            'user' => $user,
            'roles' => $roles,
            'userRoles' => $userRoles
        ], 'admin');
    }
    
    /**
     * 更新使用者
     */
    public function update($id)
    {
        if (!$this->verifyCsrf()) {
            return $this->json(['success' => false, 'message' => '安全驗證失敗']);
        }
        
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return $this->json(['success' => false, 'message' => '使用者不存在']);
        }
        
        // 檢查權限
        $currentLevel = $this->getCurrentUserLevel();
        $targetLevel = $this->userModel->getHighestLevel($id);
        
        if ($targetLevel <= $currentLevel && $id != $_SESSION['user']['id']) {
            return $this->json(['success' => false, 'message' => '您沒有權限編輯此使用者']);
        }
        
        $displayName = trim($this->input('display_name', ''));
        $email = trim($this->input('email', ''));
        $password = $this->input('password', '');
        $roleIds = $this->input('roles', []);
        $status = (int)$this->input('status', 1);
        
        // 驗證
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->json(['success' => false, 'message' => 'Email 格式不正確']);
        }
        
        if (!empty($email)) {
            $existingUser = $this->userModel->findByEmail($email);
            if ($existingUser && $existingUser['id'] != $id) {
                return $this->json(['success' => false, 'message' => 'Email 已被使用']);
            }
        }
        
        $updateData = [
            'display_name' => $displayName ?: $user['username'],
            'email' => $email,
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // 如果有輸入新密碼
        if (!empty($password)) {
            $passwordService = new PasswordService();
            $validation = $passwordService->validatePasswordStrength($password);
            if (!$validation['valid']) {
                return $this->json(['success' => false, 'message' => $validation['message']]);
            }
            $updateData['password'] = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
            $updateData['password_changed_at'] = date('Y-m-d H:i:s');
        }
        
        // 更新使用者
        $this->userModel->update($id, $updateData);
        
        // 更新角色 (如果不是編輯自己)
        if ($id != $_SESSION['user']['id'] && !empty($roleIds)) {
            // 驗證角色權限
            foreach ($roleIds as $roleId) {
                $role = $this->roleModel->find($roleId);
                if (!$role || $role['level'] <= $currentLevel) {
                    return $this->json(['success' => false, 'message' => '您沒有權限指派此角色']);
                }
            }
            $this->userModel->syncRoles($id, $roleIds);
        }
        
        return $this->json([
            'success' => true,
            'message' => '使用者更新成功'
        ]);
    }
    
    /**
     * 刪除使用者
     */
    public function delete($id)
    {
        if (!$this->verifyCsrf()) {
            return $this->json(['success' => false, 'message' => '安全驗證失敗']);
        }
        
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return $this->json(['success' => false, 'message' => '使用者不存在']);
        }
        
        // 不能刪除自己
        if ($id == $_SESSION['user']['id']) {
            return $this->json(['success' => false, 'message' => '不能刪除自己的帳號']);
        }
        
        // 檢查權限
        $currentLevel = $this->getCurrentUserLevel();
        $targetLevel = $this->userModel->getHighestLevel($id);
        
        if ($targetLevel <= $currentLevel) {
            return $this->json(['success' => false, 'message' => '您沒有權限刪除此使用者']);
        }
        
        // 軟刪除
        $this->userModel->update($id, [
            'status' => 0,
            'deleted_at' => date('Y-m-d H:i:s')
        ]);
        
        return $this->json([
            'success' => true,
            'message' => '使用者已刪除'
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
}
