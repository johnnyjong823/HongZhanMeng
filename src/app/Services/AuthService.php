<?php
namespace App\Services;

use App\Models\User;
use App\Models\LoginLog;
use App\Models\FunctionModel;

class AuthService
{
    protected $userModel;
    protected $loginLogModel;
    protected $functionModel;
    protected $config;
    
    public function __construct()
    {
        $this->userModel = new User();
        $this->loginLogModel = new LoginLog();
        $this->functionModel = new FunctionModel();
        $this->config = require ROOT_PATH . '/config/app.php';
    }
    
    /**
     * 登入
     */
    public function login($username, $password, $remember = false)
    {
        // 檢查 IP 是否被封鎖
        $ip = get_client_ip();
        if ($this->loginLogModel->isIpBlocked($ip)) {
            return [
                'success' => false,
                'message' => '此 IP 因多次登入失敗已被暫時封鎖，請稍後再試'
            ];
        }
        
        // 查詢使用者 (支援帳號或 Email 登入)
        $user = $this->userModel->findByUsername($username);
        
        if (!$user) {
            $user = $this->userModel->findByEmail($username);
        }
        
        // 使用者不存在
        if (!$user) {
            $this->loginLogModel->recordFailure($username, '帳號不存在');
            return [
                'success' => false,
                'message' => '帳號或密碼錯誤'
            ];
        }
        
        // 檢查帳號狀態
        if ($user['status'] != 1) {
            $this->loginLogModel->recordFailure($username, '帳號已停用');
            return [
                'success' => false,
                'message' => '此帳號已被停用'
            ];
        }
        
        // 檢查是否被鎖定
        if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
            $this->loginLogModel->recordFailure($username, '帳號已鎖定');
            $remainingTime = ceil((strtotime($user['locked_until']) - time()) / 60);
            return [
                'success' => false,
                'message' => "帳號已鎖定，請 {$remainingTime} 分鐘後再試"
            ];
        }
        
        // 驗證密碼
        if (!password_verify($password, $user['password'])) {
            $this->loginLogModel->recordFailure($username, '密碼錯誤');
            
            // 增加失敗次數
            $attempts = $user['login_attempts'] + 1;
            $maxAttempts = $this->config['auth']['max_attempts'] ?? 5;
            
            $updateData = ['login_attempts' => $attempts];
            
            // 超過次數則鎖定帳號
            if ($attempts >= $maxAttempts) {
                $lockoutTime = $this->config['auth']['lockout_time'] ?? 900;
                $updateData['locked_until'] = date('Y-m-d H:i:s', time() + $lockoutTime);
            }
            
            $this->userModel->update($user['id'], $updateData);
            
            $remaining = $maxAttempts - $attempts;
            if ($remaining > 0) {
                return [
                    'success' => false,
                    'message' => "帳號或密碼錯誤，還有 {$remaining} 次嘗試機會"
                ];
            } else {
                return [
                    'success' => false,
                    'message' => '登入失敗次數過多，帳號已被暫時鎖定'
                ];
            }
        }
        
        // 登入成功
        $this->userModel->update($user['id'], [
            'login_attempts' => 0,
            'locked_until' => null,
            'last_login_at' => date('Y-m-d H:i:s'),
            'last_login_ip' => $ip
        ]);
        
        // 記錄登入紀錄
        $this->loginLogModel->recordSuccess($user['id'], $user['username']);
        
        // 重新產生 Session ID
        session_regenerate_id(true);
        
        // 取得權限層級
        $level = $this->userModel->getHighestLevel($user['id']);
        
        // 設定 Session
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'display_name' => $user['display_name'],
            'avatar' => $user['avatar'],
            'status' => $user['status'],
            'level' => $level
        ];
        
        $_SESSION['last_activity'] = time();
        
        // 載入權限
        $_SESSION['permissions'] = $this->userModel->getPermissions($user['id']);
        
        // 載入選單
        $_SESSION['menu'] = $this->functionModel->getUserMenu($user['id'], $level);
        
        return [
            'success' => true,
            'message' => '登入成功',
            'user' => $_SESSION['user']
        ];
    }
    
    /**
     * 登出
     */
    public function logout()
    {
        $userId = $_SESSION['user']['id'] ?? null;
        
        // 記錄登出
        if ($userId) {
            $this->loginLogModel->recordLogout($userId);
        }
        
        // 清除 Session
        $_SESSION = [];
        
        // 刪除 Session Cookie
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        
        // 銷毀 Session
        session_destroy();
        
        return true;
    }
    
    /**
     * 檢查是否已登入
     */
    public function check()
    {
        return isset($_SESSION['user']) && !empty($_SESSION['user']['id']);
    }
    
    /**
     * 檢查是否已登入 (別名)
     */
    public function isLoggedIn()
    {
        return $this->check();
    }
    
    /**
     * 取得目前使用者
     */
    public function user()
    {
        return $_SESSION['user'] ?? null;
    }
}
