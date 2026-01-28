<?php
namespace App\Controllers;

use Core\Controller;
use App\Services\AuthService;
use App\Services\PasswordService;

class AccountController extends Controller
{
    protected $authService;
    protected $passwordService;
    protected $skipActionFilter = ['login', 'doLogin', 'logout', 'forgotPassword', 'doForgotPassword', 'resetPassword', 'doResetPassword'];
    
    public function __construct()
    {
        parent::__construct();
        $this->authService = new AuthService();
        $this->passwordService = new PasswordService();
    }
    
    /**
     * 登入頁面
     */
    public function login()
    {
        if ($this->authService->isLoggedIn()) {
            return $this->redirect('/admin');
        }
        
        return $this->view('account/login', [
            'title' => '登入 - 鴻展盟管理系統'
        ], 'blank');
    }
    
    /**
     * 處理登入
     */
    public function doLogin()
    {
        $username = $this->input('username', '');
        $password = $this->input('password', '');
        $remember = $this->input('remember', false);
        
        if (empty($username) || empty($password)) {
            return $this->json([
                'success' => false,
                'message' => '請輸入帳號和密碼'
            ]);
        }
        
        // CSRF 驗證
        if (!$this->verifyCsrf()) {
            return $this->json([
                'success' => false,
                'message' => '安全驗證失敗，請重新整理頁面'
            ]);
        }
        
        $result = $this->authService->login($username, $password);
        
        if ($result['success']) {
            return $this->json([
                'success' => true,
                'message' => '登入成功',
                'redirect' => '/admin'
            ]);
        }
        
        return $this->json([
            'success' => false,
            'message' => $result['message']
        ]);
    }
    
    /**
     * 登出
     */
    public function logout()
    {
        $this->authService->logout();
        return $this->redirect('/account/login');
    }
    
    /**
     * 忘記密碼頁面
     */
    public function forgotPassword()
    {
        return $this->view('account/forgot-password', [
            'title' => '忘記密碼 - 鴻展盟管理系統'
        ], 'blank');
    }
    
    /**
     * 處理忘記密碼
     */
    public function doForgotPassword()
    {
        $email = $this->input('email', '');
        
        if (empty($email)) {
            return $this->json([
                'success' => false,
                'message' => '請輸入 Email'
            ]);
        }
        
        if (!$this->verifyCsrf()) {
            return $this->json([
                'success' => false,
                'message' => '安全驗證失敗，請重新整理頁面'
            ]);
        }
        
        $result = $this->passwordService->sendResetLink($email);
        
        return $this->json($result);
    }
    
    /**
     * 重設密碼頁面
     */
    public function resetPassword()
    {
        $token = $_GET['token'] ?? '';
        $email = $_GET['email'] ?? '';
        
        if (empty($token) || empty($email)) {
            return $this->view('account/reset-password-error', [
                'title' => '連結無效 - 鴻展盟管理系統',
                'message' => '連結無效或已過期'
            ], 'blank');
        }
        
        $validation = $this->passwordService->validateResetToken($email, $token);
        
        if (!$validation['valid']) {
            return $this->view('account/reset-password-error', [
                'title' => '連結無效 - 鴻展盟管理系統',
                'message' => $validation['message']
            ], 'blank');
        }
        
        return $this->view('account/reset-password', [
            'title' => '重設密碼 - 鴻展盟管理系統',
            'token' => $token,
            'email' => $email
        ], 'blank');
    }
    
    /**
     * 處理重設密碼
     */
    public function doResetPassword()
    {
        $email = $this->input('email', '');
        $token = $this->input('token', '');
        $password = $this->input('password', '');
        $confirmPassword = $this->input('confirm_password', '');
        
        if (empty($password) || empty($confirmPassword)) {
            return $this->json([
                'success' => false,
                'message' => '請輸入新密碼'
            ]);
        }
        
        if ($password !== $confirmPassword) {
            return $this->json([
                'success' => false,
                'message' => '兩次密碼輸入不一致'
            ]);
        }
        
        if (!$this->verifyCsrf()) {
            return $this->json([
                'success' => false,
                'message' => '安全驗證失敗，請重新整理頁面'
            ]);
        }
        
        $result = $this->passwordService->resetPassword($email, $token, $password);
        
        if ($result['success']) {
            $result['redirect'] = '/account/login';
        }
        
        return $this->json($result);
    }
    
    /**
     * 修改密碼頁面 (需登入)
     */
    public function changePassword()
    {
        return $this->view('account/change-password', [
            'title' => '修改密碼'
        ], 'admin');
    }
    
    /**
     * 處理修改密碼 (需登入)
     */
    public function doChangePassword()
    {
        $currentPassword = $this->input('current_password', '');
        $newPassword = $this->input('new_password', '');
        $confirmPassword = $this->input('confirm_password', '');
        
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            return $this->json([
                'success' => false,
                'message' => '請填寫所有欄位'
            ]);
        }
        
        if ($newPassword !== $confirmPassword) {
            return $this->json([
                'success' => false,
                'message' => '新密碼與確認密碼不一致'
            ]);
        }
        
        if (!$this->verifyCsrf()) {
            return $this->json([
                'success' => false,
                'message' => '安全驗證失敗，請重新整理頁面'
            ]);
        }
        
        $userId = $_SESSION['user']['id'] ?? 0;
        $result = $this->passwordService->changePassword($userId, $currentPassword, $newPassword);
        
        return $this->json($result);
    }
    
    /**
     * 個人資料頁面 (需登入)
     */
    public function profile()
    {
        return $this->view('account/profile', [
            'title' => '個人資料'
        ], 'admin');
    }
    
    /**
     * 更新個人資料 (需登入)
     */
    public function updateProfile()
    {
        $displayName = $this->input('display_name', '');
        $email = $this->input('email', '');
        
        if (!$this->verifyCsrf()) {
            return $this->json([
                'success' => false,
                'message' => '安全驗證失敗，請重新整理頁面'
            ]);
        }
        
        $userId = $_SESSION['user']['id'] ?? 0;
        
        $userModel = new \App\Models\User();
        $userModel->update($userId, [
            'display_name' => $displayName,
            'email' => $email
        ]);
        
        // 更新 Session
        $_SESSION['user']['display_name'] = $displayName;
        $_SESSION['user']['email'] = $email;
        
        return $this->json([
            'success' => true,
            'message' => '個人資料已更新'
        ]);
    }
}
