<?php
/**
 * PHP 內建伺服器路由
 * 用於開發環境，處理靜態資源和 PHP 請求
 */

$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);

// 檢查是否為靜態資源
$publicPath = __DIR__ . $path;

if ($path !== '/' && file_exists($publicPath) && is_file($publicPath)) {
    // 靜態資源，讓 PHP 內建伺服器處理
    return false;
}

// 其他請求交給 index.php 處理
require __DIR__ . '/index.php';
