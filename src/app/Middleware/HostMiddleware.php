<?php
namespace App\Middleware;

class HostMiddleware
{
    /**
     * 處理中介層 - 允許 admin 和 host 存取
     */
    public function handle()
    {
        $userLevel = $_SESSION['user']['level'] ?? 999;
        
        // 允許 admin (1) 和 host (2)
        if ($userLevel > 2) {
            http_response_code(403);
            include ROOT_PATH . '/app/Views/errors/403.php';
            exit;
        }
    }
}
