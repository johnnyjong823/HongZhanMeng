<?php
namespace Core;

use App\Filters\RecordActionFilter;

abstract class Controller
{
    protected $view;
    
    public function __construct()
    {
        $this->view = new View();
    }
    
    /**
     * 記錄操作 (手動呼叫)
     */
    protected function recordAction($action = null, $description = null)
    {
        $filter = new RecordActionFilter();
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $method = $backtrace[1]['function'] ?? 'unknown';
        
        $filter->record(
            get_class($this),
            $method,
            $action,
            $description
        );
    }
    
    /**
     * 渲染視圖
     */
    protected function render($template, $data = [], $layout = null)
    {
        $this->view->render($template, $data, $layout);
    }
    
    /**
     * 渲染視圖 (別名)
     */
    protected function view($template, $data = [], $layout = null)
    {
        $this->view->render($template, $data, $layout);
    }
    
    /**
     * 重新導向
     */
    protected function redirect($url, $statusCode = 302)
    {
        header('Location: ' . $url, true, $statusCode);
        exit;
    }
    
    /**
     * 回傳 JSON
     */
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    /**
     * 取得目前登入使用者
     */
    protected function currentUser()
    {
        return $_SESSION['user'] ?? null;
    }
    
    /**
     * 取得目前使用者 ID
     */
    protected function currentUserId()
    {
        return $_SESSION['user']['id'] ?? null;
    }
    
    /**
     * 檢查是否已登入
     */
    protected function isLoggedIn()
    {
        return isset($_SESSION['user']) && !empty($_SESSION['user']['id']);
    }
    
    /**
     * 檢查權限層級
     */
    protected function hasLevel($requiredLevel)
    {
        $user = $this->currentUser();
        if (!$user) return false;
        
        return ($user['level'] ?? 999) <= $requiredLevel;
    }
    
    /**
     * 檢查是否為 admin
     */
    protected function isAdmin()
    {
        return $this->hasLevel(1);
    }
    
    /**
     * 檢查是否為 host 以上
     */
    protected function isHost()
    {
        return $this->hasLevel(2);
    }
    
    /**
     * 檢查功能權限
     */
    protected function hasPermission($functionCode, $action = 'view')
    {
        $user = $this->currentUser();
        if (!$user) return false;
        
        // admin 擁有所有權限
        if ($this->isAdmin()) return true;
        
        $permissions = $_SESSION['permissions'] ?? [];
        
        if (!isset($permissions[$functionCode])) {
            return false;
        }
        
        $actionMap = [
            'view' => 'can_view',
            'create' => 'can_create',
            'edit' => 'can_edit',
            'delete' => 'can_delete'
        ];
        
        $permKey = $actionMap[$action] ?? 'can_view';
        
        return $permissions[$functionCode][$permKey] ?? false;
    }
    
    /**
     * 產生 CSRF Token
     */
    protected function generateCsrfToken()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * 驗證 CSRF Token
     */
    protected function validateCsrfToken($token)
    {
        if (empty($_SESSION['csrf_token'])) {
            return false;
        }
        
        return hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * 驗證 CSRF Token (從請求中取得)
     */
    protected function verifyCsrf()
    {
        $token = $this->input('csrf_token') ?? $this->input('_token') ?? null;
        
        // 也檢查 Header
        if (empty($token)) {
            $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
        }
        
        if (empty($token)) {
            return false;
        }
        
        return $this->validateCsrfToken($token);
    }
    
    /**
     * 取得 POST 資料
     */
    protected function post($key = null, $default = null)
    {
        if ($key === null) {
            return $_POST;
        }
        
        return $_POST[$key] ?? $default;
    }
    
    /**
     * 取得 GET 資料
     */
    protected function get($key = null, $default = null)
    {
        if ($key === null) {
            return $_GET;
        }
        
        return $_GET[$key] ?? $default;
    }
    
    /**
     * 取得輸入資料 (GET、POST 或 JSON)
     */
    protected function input($key = null, $default = null)
    {
        // 檢查是否為 JSON 請求
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        
        if (strpos($contentType, 'application/json') !== false) {
            static $jsonData = null;
            
            if ($jsonData === null) {
                $rawInput = file_get_contents('php://input');
                $jsonData = json_decode($rawInput, true) ?? [];
            }
            
            if ($key === null) {
                return array_merge($_GET, $jsonData);
            }
            
            return $jsonData[$key] ?? $_GET[$key] ?? $default;
        }
        
        if ($key === null) {
            return array_merge($_GET, $_POST);
        }
        
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }
    
    /**
     * 設定 Flash 訊息
     */
    protected function flash($type, $message)
    {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }
    
    /**
     * 取得 Flash 訊息
     */
    protected function getFlash()
    {
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $flash;
    }
}
