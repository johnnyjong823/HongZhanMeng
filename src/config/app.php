<?php
/**
 * 應用程式設定
 */

return [
    // 應用程式名稱
    'name' => '鴻展盟管理系統',
    
    // 除錯模式 (正式環境請設為 false)
    'debug' => true,
    
    // 網站網址 (不含結尾斜線)
    'url' => 'http://localhost:8801',
    
    // 基礎路徑 (子目錄部署時使用，例如 '/Final/public'，根目錄部署時設為 '/')
    'base_path' => '/',
    
    // 時區
    'timezone' => 'Asia/Taipei',
    
    // Session 設定
    'session' => [
        'lifetime' => 3600,       // 60 分鐘 (秒)
        'path' => '/',
        'domain' => null,
        'secure' => false,        // 本機開發設 false，正式環境 HTTPS 設 true
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
