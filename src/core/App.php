<?php
namespace Core;

class App
{
    protected $config;
    protected $router;
    
    public function __construct()
    {
        // 載入設定
        $this->config = require ROOT_PATH . '/config/app.php';
        
        // 設定時區
        date_default_timezone_set($this->config['timezone'] ?? 'Asia/Taipei');
        
        // 設定錯誤處理
        $this->initErrorHandling();
        
        // 初始化 Session
        $this->initSession();
        
        // 初始化路由器
        $this->initRouter();
    }
    
    /**
     * 初始化錯誤處理
     */
    protected function initErrorHandling()
    {
        if ($this->config['debug'] ?? false) {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        } else {
            error_reporting(0);
            ini_set('display_errors', 0);
        }
    }
    
    /**
     * 初始化 Session
     */
    protected function initSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            $sessionConfig = $this->config['session'] ?? [];
            
            // Session 設定
            ini_set('session.cookie_httponly', $sessionConfig['httponly'] ?? 1);
            ini_set('session.use_strict_mode', 1);
            ini_set('session.cookie_samesite', $sessionConfig['samesite'] ?? 'Lax');
            
            if ($sessionConfig['secure'] ?? false) {
                ini_set('session.cookie_secure', 1);
            }
            
            // Session 有效時間
            $lifetime = $sessionConfig['lifetime'] ?? 3600;
            ini_set('session.gc_maxlifetime', $lifetime);
            session_set_cookie_params($lifetime);
            
            session_start();
        }
        
        // 檢查 Session 是否過期
        $this->checkSessionTimeout();
    }
    
    /**
     * 檢查 Session 逾時
     */
    protected function checkSessionTimeout()
    {
        $timeout = $this->config['session']['lifetime'] ?? 3600;
        
        if (isset($_SESSION['last_activity'])) {
            if (time() - $_SESSION['last_activity'] > $timeout) {
                // Session 過期，清除
                session_unset();
                session_destroy();
                session_start();
                
                // 如果是後台請求，重導向到登入頁
                if ($this->isBackendRequest()) {
                    header('Location: /account/login?expired=1');
                    exit;
                }
            }
        }
        
        // 更新最後活動時間
        $_SESSION['last_activity'] = time();
    }
    
    /**
     * 判斷是否為後台請求
     */
    protected function isBackendRequest()
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        return strpos($uri, '/admin') === 0;
    }
    
    /**
     * 初始化路由器
     */
    protected function initRouter()
    {
        $this->router = new Router();
        $routes = require ROOT_PATH . '/config/routes.php';
        
        foreach ($routes as $route) {
            $this->router->addRoute(
                $route['method'],
                $route['path'],
                $route['controller'],
                $route['action'],
                $route['middleware'] ?? []
            );
        }
    }
    
    /**
     * 執行應用程式
     */
    public function run()
    {
        try {
            $this->router->dispatch();
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }
    
    /**
     * 例外處理
     */
    protected function handleException(\Exception $e)
    {
        $code = $e->getCode();
        
        // PDOException 的 getCode() 返回 SQLSTATE 字串（如 '42S22'），不是 HTTP 狀態碼
        if (!is_int($code) || $code < 100 || $code >= 600) {
            $code = 500;
        }
        
        http_response_code($code);
        
        if ($this->config['debug'] ?? false) {
            echo '<h1>Error ' . $code . ': ' . htmlspecialchars($e->getMessage()) . '</h1>';
            echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
        } else {
            $errorFile = ROOT_PATH . '/app/Views/errors/' . $code . '.php';
            
            if (file_exists($errorFile)) {
                include $errorFile;
            } else {
                echo '<h1>Error ' . $code . '</h1>';
                echo '<p>發生錯誤，請稍後再試。</p>';
            }
        }
    }
}
