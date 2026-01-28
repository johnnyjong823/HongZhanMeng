<?php
/**
 * cPanel 部署用應用程式設定
 * 
 * 使用方式：
 * 1. 複製此檔案到 config/app.php
 * 2. 根據實際情況修改 'url' 和 'base_path' 設定
 * 
 * 測試階段 (Final 資料夾)：
 *   'url' => 'https://your-domain.com/Final/public'
 *   'base_path' => '/Final/public'
 * 
 * 正式上線：
 *   'url' => 'https://your-domain.com'
 *   'base_path' => '/'
 */

return [
    // 應用程式名稱
    'name' => '鴻展盟管理系統',
    
    // 除錯模式 (正式環境請設為 false)
    // 測試階段建議設為 true 方便除錯
    'debug' => true,
    
    // 網站網址 (不含結尾斜線)
    // ⚠️ 請根據實際情況修改
    // 測試階段：'https://your-domain.com/Final/public'
    // 正式上線：'https://your-domain.com'
    'url' => 'https://your-domain.com/Final/public',
    
    // 基礎路徑 (子目錄部署時使用)
    // ⚠️ 請根據實際情況修改
    // 測試階段：'/Final/public'
    // 正式上線：'/'
    'base_path' => '/Final/public',
    
    // 時區
    'timezone' => 'Asia/Taipei',
    
    // Session 設定
    'session' => [
        'lifetime' => 3600,       // 60 分鐘 (秒)
        'path' => '/',
        'domain' => null,
        'secure' => true,         // HTTPS 環境設為 true
        'httponly' => true,       // 禁止 JavaScript 存取
        'samesite' => 'Lax',
    ],
    
    // 登入設定
    'auth' => [
        'max_attempts' => 5,           // 最大嘗試次數
        'lockout_time' => 900,         // 鎖定時間 (15 分鐘)
        'password_min_length' => 8,    // 密碼最小長度
    ],
    
    // 分頁設定
    'pagination' => [
        'per_page' => 20,
    ],
];
