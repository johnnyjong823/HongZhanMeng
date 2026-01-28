<?php
/**
 * 郵件設定
 */

return [
    // 寄件者資訊
    'from_email' => 'noreply@example.com',
    'from_name' => '鴻展盟管理系統',
    
    // SMTP 設定 (選用，cPanel 主機通常使用 PHP mail())
    'use_smtp' => false,
    'smtp' => [
        'host' => 'smtp.example.com',
        'port' => 587,
        'username' => '',
        'password' => '',
        'encryption' => 'tls',  // tls 或 ssl
    ],
];
