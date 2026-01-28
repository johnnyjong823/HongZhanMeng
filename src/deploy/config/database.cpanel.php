<?php
/**
 * cPanel 部署用資料庫設定
 * 
 * 使用方式：
 * 1. 複製此檔案到 config/database.php
 * 2. 修改以下設定為 cPanel 的資料庫資訊
 * 
 * ⚠️ 注意：cPanel 會自動在資料庫名稱和使用者名稱前加上帳號前綴
 * 例如：帳號是 myuser，建立資料庫 hzm_db
 *      實際資料庫名稱會是：myuser_hzm_db
 */

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

// 只有在 pdo_mysql 擴展載入時才加入 MYSQL_ATTR_INIT_COMMAND
if (defined('PDO::MYSQL_ATTR_INIT_COMMAND')) {
    $options[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci";
}

return [
    // cPanel 通常是 localhost
    'host' => 'localhost',
    
    // MySQL 預設埠
    'port' => '3306',
    
    // ⚠️ 請修改為實際的資料庫名稱 (含 cPanel 前綴)
    // 格式：cpanel帳號_資料庫名稱
    'database' => 'cpanel帳號_hongzhanmeng',
    
    // ⚠️ 請修改為實際的使用者名稱 (含 cPanel 前綴)
    // 格式：cpanel帳號_使用者名稱
    'username' => 'cpanel帳號_dbuser',
    
    // ⚠️ 請修改為實際的密碼
    'password' => '請輸入資料庫密碼',
    
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'options' => $options,
];
