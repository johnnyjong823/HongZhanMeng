<?php
namespace App\Middleware;

class AdminMiddleware
{
    /**
     * 處理中介層 - 只允許 admin 存取
     */
    public function handle()
    {
        $userLevel = $_SESSION['user']['level'] ?? 999;
        
        // 只允許 admin (level = 1)
        if ($userLevel > 1) {
            http_response_code(403);
            include ROOT_PATH . '/app/Views/errors/403.php';
            exit;
        }
    }
}
