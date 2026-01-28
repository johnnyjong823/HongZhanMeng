<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\User;
use App\Models\ActionLog;
use App\Models\LoginLog;

class DashboardController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * 後台首頁 (儀表板)
     */
    public function index()
    {
        $userModel = new User();
        $actionLogModel = new ActionLog();
        $loginLogModel = new LoginLog();
        
        // 統計資料
        $stats = [
            'total_users' => $userModel->count(['status' => 1]),
            'recent_actions' => $actionLogModel->count([]),
            'today_logins' => $loginLogModel->countTodayLogins(),
            'online_users' => $this->getOnlineUsersCount()
        ];
        
        // 最近操作紀錄
        $recentActions = $actionLogModel->getRecent(10);
        
        // 最近登入紀錄
        $recentLogins = $loginLogModel->getRecent(10);
        
        return $this->view('admin/dashboard/index', [
            'title' => '儀表板',
            'stats' => $stats,
            'recentActions' => $recentActions,
            'recentLogins' => $recentLogins
        ], 'admin');
    }
    
    /**
     * 取得線上使用者數量 (簡易版)
     */
    protected function getOnlineUsersCount()
    {
        $userModel = new User();
        $timeout = config('session.timeout', 60);
        $datetime = date('Y-m-d H:i:s', strtotime("-{$timeout} minutes"));
        
        // 使用原生 SQL 查詢
        $sql = "SELECT COUNT(*) as count FROM acusers WHERE last_login_at >= :datetime AND status = 1";
        $result = $userModel->raw($sql, ['datetime' => $datetime])->fetch();
        
        return (int) ($result['count'] ?? 0);
    }
    
    /**
     * 系統資訊
     */
    public function systemInfo()
    {
        $info = [
            'php_version' => phpversion(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? '',
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'mysql_version' => $this->getMysqlVersion(),
            'disk_free_space' => $this->formatBytes(disk_free_space('.')),
            'disk_total_space' => $this->formatBytes(disk_total_space('.'))
        ];
        
        return $this->view('admin/dashboard/system-info', [
            'title' => '系統資訊',
            'info' => $info
        ], 'admin');
    }
    
    /**
     * 取得 MySQL 版本
     */
    protected function getMysqlVersion()
    {
        try {
            $db = \Core\Database::getInstance();
            $stmt = $db->query("SELECT VERSION() as version");
            $result = $stmt->fetch();
            return $result['version'] ?? 'Unknown';
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }
    
    /**
     * 格式化位元組
     */
    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
