<?php
namespace App\Middleware;

class AuthMiddleware
{
    /**
     * 處理中介層
     */
    public function handle()
    {
        // 檢查是否已登入
        if (!isset($_SESSION['user']) || empty($_SESSION['user']['id'])) {
            // 儲存原本要訪問的網址
            $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
            
            header('Location: ' . url('/account/login'));
            exit;
        }
        
        // 檢查帳號狀態
        if (($_SESSION['user']['status'] ?? 0) != 1) {
            session_destroy();
            header('Location: ' . url('/account/login?error=disabled'));
            exit;
        }
    }
}
