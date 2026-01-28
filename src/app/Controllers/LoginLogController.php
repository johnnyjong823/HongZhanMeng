<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\LoginLog;

class LoginLogController extends Controller
{
    protected $loginLogModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->loginLogModel = new LoginLog();
    }
    
    /**
     * 登入紀錄列表
     */
    public function index()
    {
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 50;
        
        // 篩選條件
        $filters = [
            'user_id' => $_GET['user_id'] ?? '',
            'status' => $_GET['status'] ?? '',
            'date_from' => $_GET['date_from'] ?? '',
            'date_to' => $_GET['date_to'] ?? '',
            'ip' => $_GET['ip'] ?? ''
        ];
        
        $conditions = [];
        $params = [];
        
        if (!empty($filters['user_id'])) {
            $conditions[] = "l.user_id = ?";
            $params[] = (int)$filters['user_id'];
        }
        
        if ($filters['status'] !== '') {
            $conditions[] = "l.login_status = ?";
            $params[] = (int)$filters['status'];
        }
        
        if (!empty($filters['date_from'])) {
            $conditions[] = "l.login_at >= ?";
            $params[] = $filters['date_from'] . ' 00:00:00';
        }
        
        if (!empty($filters['date_to'])) {
            $conditions[] = "l.login_at <= ?";
            $params[] = $filters['date_to'] . ' 23:59:59';
        }
        
        if (!empty($filters['ip'])) {
            $conditions[] = "l.ip_address LIKE ?";
            $params[] = "%{$filters['ip']}%";
        }
        
        $logs = $this->loginLogModel->paginateWithUser($page, $perPage, $conditions, $params);
        
        // 取得使用者列表供篩選
        $userModel = new \App\Models\User();
        $users = $userModel->where('status', 1);
        
        // 統計資料
        $stats = $this->loginLogModel->getStatistics(30);
        
        return $this->view('admin/login-logs/index', [
            'title' => '登入紀錄',
            'logs' => $logs['data'],
            'pagination' => $logs['pagination'],
            'filters' => $filters,
            'users' => $users,
            'stats' => $stats
        ], 'admin');
    }
    
    /**
     * 登入統計
     */
    public function statistics()
    {
        $days = (int)($_GET['days'] ?? 30);
        
        $stats = $this->loginLogModel->getStatistics($days);
        $dailyStats = $this->loginLogModel->getDailyStatistics($days);
        $topUsers = $this->loginLogModel->getTopUsers($days, 10);
        $failedAttempts = $this->loginLogModel->getFailedAttempts($days, 20);
        
        return $this->view('admin/login-logs/statistics', [
            'title' => '登入統計',
            'days' => $days,
            'stats' => $stats,
            'dailyStats' => $dailyStats,
            'topUsers' => $topUsers,
            'failedAttempts' => $failedAttempts
        ], 'admin');
    }
    
    /**
     * 匯出登入紀錄
     */
    public function export()
    {
        // 篩選條件
        $filters = [
            'user_id' => $_GET['user_id'] ?? '',
            'status' => $_GET['status'] ?? '',
            'date_from' => $_GET['date_from'] ?? '',
            'date_to' => $_GET['date_to'] ?? ''
        ];
        
        $conditions = [];
        $params = [];
        
        if (!empty($filters['user_id'])) {
            $conditions[] = "l.user_id = ?";
            $params[] = (int)$filters['user_id'];
        }
        
        if ($filters['status'] !== '') {
            $conditions[] = "l.login_status = ?";
            $params[] = (int)$filters['status'];
        }
        
        if (!empty($filters['date_from'])) {
            $conditions[] = "l.login_at >= ?";
            $params[] = $filters['date_from'] . ' 00:00:00';
        }
        
        if (!empty($filters['date_to'])) {
            $conditions[] = "l.login_at <= ?";
            $params[] = $filters['date_to'] . ' 23:59:59';
        }
        
        $logs = $this->loginLogModel->getWithUser($conditions, $params, 10000);
        
        // 產生 CSV
        $filename = 'login_logs_' . date('Y-m-d_His') . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // BOM for Excel
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
        
        // Header
        fputcsv($output, ['ID', '使用者', '狀態', 'IP 位址', '使用者代理', '登入時間']);
        
        // Data
        foreach ($logs as $log) {
            $status = ($log['login_status'] ?? 0) == 1 ? '成功' : '失敗';
            
            fputcsv($output, [
                $log['id'],
                $log['username'] ?? 'N/A',
                $status,
                $log['ip_address'],
                $log['user_agent'],
                $log['login_at']
            ]);
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * API: 取得登入統計圖表資料
     */
    public function chartData()
    {
        $days = (int)($_GET['days'] ?? 7);
        
        $dailyStats = $this->loginLogModel->getDailyStatistics($days);
        
        $labels = [];
        $successData = [];
        $failedData = [];
        
        foreach ($dailyStats as $stat) {
            $labels[] = date('m/d', strtotime($stat['date']));
            $successData[] = (int)$stat['success_count'];
            $failedData[] = (int)$stat['failed_count'];
        }
        
        return $this->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => '成功登入',
                    'data' => $successData,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)'
                ],
                [
                    'label' => '失敗嘗試',
                    'data' => $failedData,
                    'borderColor' => '#ef4444',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)'
                ]
            ]
        ]);
    }
}
