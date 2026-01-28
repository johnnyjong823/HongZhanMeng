<?php
/**
 * 應用程式入口點
 */

// 定義根目錄
define('ROOT_PATH', dirname(__DIR__));

// 錯誤處理
error_reporting(E_ALL);
ini_set('display_errors', 0);

// 載入自動載入器 (會自動註冊)
require_once ROOT_PATH . '/core/Autoloader.php';

// 載入設定
$config = require ROOT_PATH . '/config/app.php';

// 設定時區
date_default_timezone_set($config['timezone'] ?? 'Asia/Taipei');

// 設定錯誤顯示 (依據 debug 模式)
if ($config['debug'] ?? false) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// 自訂錯誤處理
set_exception_handler(function ($e) use ($config) {
    $logDir = ROOT_PATH . '/storage/logs';
    
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $log = sprintf(
        "[%s] %s: %s in %s:%d\nStack trace:\n%s\n\n",
        date('Y-m-d H:i:s'),
        get_class($e),
        $e->getMessage(),
        $e->getFile(),
        $e->getLine(),
        $e->getTraceAsString()
    );
    
    file_put_contents($logDir . '/error.log', $log, FILE_APPEND);
    
    if ($config['debug'] ?? false) {
        echo "<h1>Error</h1>";
        echo "<p><strong>" . get_class($e) . ":</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p>File: " . htmlspecialchars($e->getFile()) . " Line: " . $e->getLine() . "</p>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    } else {
        http_response_code(500);
        echo "<h1>500 - Internal Server Error</h1>";
        echo "<p>抱歉，系統發生錯誤，請稍後再試。</p>";
    }
});

// 建立並執行應用程式
$app = new Core\App();
$app->run();
