<?php
/**
 * 自動載入器
 */

spl_autoload_register(function ($class) {
    // 命名空間對應目錄
    $namespaceMap = [
        'Core\\' => ROOT_PATH . '/core/',
        'App\\Controllers\\' => ROOT_PATH . '/app/Controllers/',
        'App\\Models\\' => ROOT_PATH . '/app/Models/',
        'App\\Middleware\\' => ROOT_PATH . '/app/Middleware/',
        'App\\Filters\\' => ROOT_PATH . '/app/Filters/',
        'App\\Services\\' => ROOT_PATH . '/app/Services/',
        'App\\Helpers\\' => ROOT_PATH . '/app/Helpers/',
    ];
    
    foreach ($namespaceMap as $namespace => $directory) {
        if (strpos($class, $namespace) === 0) {
            $relativeClass = substr($class, strlen($namespace));
            $file = $directory . str_replace('\\', '/', $relativeClass) . '.php';
            
            if (file_exists($file)) {
                require_once $file;
                return true;
            }
        }
    }
    
    return false;
});

// 載入輔助函式
require_once ROOT_PATH . '/app/Helpers/functions.php';
