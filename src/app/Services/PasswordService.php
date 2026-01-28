<?php
namespace App\Services;

use App\Models\User;

class PasswordService
{
    protected $userModel;
    protected $mailService;
    protected $config;
    
    public function __construct()
    {
        $this->userModel = new User();
        $this->mailService = new MailService();
        $this->config = require ROOT_PATH . '/config/app.php';
    }
    
    /**
     * 修改密碼
     */
    public function changePassword($userId, $currentPassword, $newPassword)
    {
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            return ['success' => false, 'message' => '使用者不存在'];
        }
        
        // 驗證目前密碼
        if (!password_verify($currentPassword, $user['password'])) {
            return ['success' => false, 'message' => '目前密碼不正確'];
        }
        
        // 驗證新密碼強度
        $validation = $this->validatePasswordStrength($newPassword);
        if (!$validation['valid']) {
            return ['success' => false, 'message' => $validation['message']];
        }
        
        // 檢查新密碼是否與舊密碼相同
        if (password_verify($newPassword, $user['password'])) {
            return ['success' => false, 'message' => '新密碼不能與目前密碼相同'];
        }
        
        // 更新密碼
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 10]);
        
        $this->userModel->update($userId, [
            'password' => $hashedPassword,
            'password_changed_at' => date('Y-m-d H:i:s')
        ]);
        
        return ['success' => true, 'message' => '密碼已更新成功'];
    }
    
    /**
     * 發送密碼重設連結
     */
    public function sendResetLink($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Email 格式不正確'];
        }
        
        $user = $this->userModel->findByEmail($email);
        
        // 無論使用者是否存在，都顯示相同訊息
        if (!$user || $user['status'] != 1) {
            return ['success' => true, 'message' => '如果此 Email 已註冊，將會收到密碼重設信件'];
        }
        
        // 產生重設 Token
        $token = bin2hex(random_bytes(32));
        $hashedToken = hash('sha256', $token);
        $expiresAt = date('Y-m-d H:i:s', time() + 3600);
        
        // 儲存 Token
        $this->userModel->update($user['id'], [
            'reset_token' => $hashedToken,
            'reset_token_expires_at' => $expiresAt
        ]);
        
        // 產生重設連結
        $baseUrl = $this->config['url'] ?? 'http://localhost:8801';
        $resetUrl = "{$baseUrl}/account/reset-password?token={$token}&email=" . urlencode($email);
        
        // 發送郵件
        $this->mailService->sendPasswordReset(
            $user['email'], 
            $user['display_name'] ?? $user['username'], 
            $resetUrl
        );
        
        return ['success' => true, 'message' => '如果此 Email 已註冊，將會收到密碼重設信件'];
    }
    
    /**
     * 驗證重設 Token
     */
    public function validateResetToken($email, $token)
    {
        $user = $this->userModel->findByEmail($email);
        
        if (!$user || !$user['reset_token']) {
            return ['valid' => false, 'message' => '連結無效或已過期'];
        }
        
        $hashedToken = hash('sha256', $token);
        
        if (!hash_equals($user['reset_token'], $hashedToken)) {
            return ['valid' => false, 'message' => '連結無效或已過期'];
        }
        
        if (strtotime($user['reset_token_expires_at']) < time()) {
            return ['valid' => false, 'message' => '連結已過期，請重新申請'];
        }
        
        return ['valid' => true, 'user' => $user, 'message' => ''];
    }
    
    /**
     * 重設密碼
     */
    public function resetPassword($email, $token, $newPassword)
    {
        $validation = $this->validateResetToken($email, $token);
        
        if (!$validation['valid']) {
            return ['success' => false, 'message' => $validation['message']];
        }
        
        $user = $validation['user'];
        
        // 驗證新密碼強度
        $strengthValidation = $this->validatePasswordStrength($newPassword);
        if (!$strengthValidation['valid']) {
            return ['success' => false, 'message' => $strengthValidation['message']];
        }
        
        // 更新密碼
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 10]);
        
        $this->userModel->update($user['id'], [
            'password' => $hashedPassword,
            'password_changed_at' => date('Y-m-d H:i:s'),
            'reset_token' => null,
            'reset_token_expires_at' => null,
            'login_attempts' => 0,
            'locked_until' => null
        ]);
        
        return ['success' => true, 'message' => '密碼已重設成功，請使用新密碼登入'];
    }
    
    /**
     * 驗證密碼強度
     */
    public function validatePasswordStrength($password)
    {
        $minLength = $this->config['auth']['password_min_length'] ?? 8;
        
        if (strlen($password) < $minLength) {
            return ['valid' => false, 'message' => "密碼長度至少需要 {$minLength} 個字元"];
        }
        
        if (!preg_match('/[a-zA-Z]/', $password)) {
            return ['valid' => false, 'message' => '密碼必須包含英文字母'];
        }
        
        if (!preg_match('/[0-9]/', $password)) {
            return ['valid' => false, 'message' => '密碼必須包含數字'];
        }
        
        return ['valid' => true, 'message' => ''];
    }
    
    /**
     * 管理員重設使用者密碼
     */
    public function adminResetPassword($adminId, $userId, $newPassword)
    {
        $adminLevel = $this->userModel->getHighestLevel($adminId);
        $userLevel = $this->userModel->getHighestLevel($userId);
        
        if ($userLevel <= $adminLevel) {
            return ['success' => false, 'message' => '您沒有權限重設此使用者的密碼'];
        }
        
        $validation = $this->validatePasswordStrength($newPassword);
        if (!$validation['valid']) {
            return ['success' => false, 'message' => $validation['message']];
        }
        
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 10]);
        
        $this->userModel->update($userId, [
            'password' => $hashedPassword,
            'password_changed_at' => date('Y-m-d H:i:s'),
            'login_attempts' => 0,
            'locked_until' => null
        ]);
        
        return ['success' => true, 'message' => '密碼已重設成功'];
    }
}
