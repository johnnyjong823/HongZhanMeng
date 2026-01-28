<?php
namespace Core;

class Router
{
    protected $routes = [];
    
    /**
     * 新增路由
     */
    public function addRoute($method, $path, $controller, $action, $middleware = [])
    {
        // 將路由路徑轉換為正規表達式
        $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $path);
        $pattern = '#^' . $pattern . '$#i';
        
        $this->routes[] = [
            'method' => strtoupper($method),
            'pattern' => $pattern,
            'path' => $path,
            'controller' => $controller,
            'action' => $action,
            'middleware' => $middleware
        ];
    }
    
    /**
     * 路由分發
     */
    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $this->getUri();
        
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method && $route['method'] !== 'ANY') {
                continue;
            }
            
            if (preg_match($route['pattern'], $uri, $matches)) {
                // 取得路由參數
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                
                // 執行中介層
                $this->runMiddleware($route['middleware']);
                
                // 呼叫控制器
                $this->callController($route['controller'], $route['action'], $params);
                return;
            }
        }
        
        // 找不到路由
        throw new \Exception('Page not found', 404);
    }
    
    /**
     * 取得請求 URI
     */
    protected function getUri()
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        
        // 移除 query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }
        
        // 移除子目錄前綴 (支援子目錄部署)
        $basePath = $this->getBasePath();
        if ($basePath !== '/' && strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }
        
        // 確保以 / 開頭
        $uri = '/' . trim($uri, '/');
        
        // 轉小寫
        $uri = strtolower($uri);
        
        // 如果只是 / 就返回 /
        if ($uri === '/') {
            return '/';
        }
        
        return $uri;
    }
    
    /**
     * 取得應用程式基礎路徑
     */
    protected function getBasePath()
    {
        static $basePath = null;
        
        if ($basePath === null) {
            $config = require ROOT_PATH . '/config/app.php';
            $basePath = $config['base_path'] ?? '/';
            $basePath = rtrim($basePath, '/');
            if ($basePath === '') {
                $basePath = '/';
            }
        }
        
        return $basePath;
    }
    
    /**
     * 執行中介層
     */
    protected function runMiddleware(array $middleware)
    {
        foreach ($middleware as $middlewareClass) {
            if (class_exists($middlewareClass)) {
                $instance = new $middlewareClass();
                $instance->handle();
            }
        }
    }
    
    /**
     * 呼叫控制器
     */
    protected function callController($controller, $action, $params)
    {
        $controllerClass = 'App\\Controllers\\' . $controller;
        
        if (!class_exists($controllerClass)) {
            throw new \Exception("Controller {$controller} not found", 500);
        }
        
        $instance = new $controllerClass();
        
        if (!method_exists($instance, $action)) {
            throw new \Exception("Action {$action} not found in {$controller}", 500);
        }
        
        // 呼叫控制器方法，傳入路由參數
        call_user_func_array([$instance, $action], array_values($params));
    }
}
