<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\ActionLog;

class ActionLogController extends Controller
{
    protected $actionLogModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->actionLogModel = new ActionLog();
    }
    
    /**
     * 操作紀錄列表
     */
    public function index()
    {
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 50;
        
        // 篩選條件
        $filters = [
            'user_id' => $_GET['user_id'] ?? '',
            'controller' => $_GET['controller'] ?? '',
            'action' => $_GET['action'] ?? '',
            'date_from' => $_GET['date_from'] ?? '',
            'date_to' => $_GET['date_to'] ?? '',
            'ip' => $_GET['ip'] ?? ''
        ];
        
        $conditions = [];
        $params = [];
        
        if (!empty($filters['user_id'])) {
            $conditions[] = "user_id = ?";
            $params[] = (int)$filters['user_id'];
        }
        
        if (!empty($filters['controller'])) {
            $conditions[] = "controller LIKE ?";
            $params[] = "%{$filters['controller']}%";
        }
        
        if (!empty($filters['action'])) {
            $conditions[] = "action LIKE ?";
            $params[] = "%{$filters['action']}%";
        }
        
        if (!empty($filters['date_from'])) {
            $conditions[] = "created_at >= ?";
            $params[] = $filters['date_from'] . ' 00:00:00';
        }
        
        if (!empty($filters['date_to'])) {
            $conditions[] = "created_at <= ?";
            $params[] = $filters['date_to'] . ' 23:59:59';
        }
        
        if (!empty($filters['ip'])) {
            $conditions[] = "ip_address LIKE ?";
            $params[] = "%{$filters['ip']}%";
        }
        
        $logs = $this->actionLogModel->paginateWithUser($page, $perPage, $conditions, $params);
        
        // 取得使用者列表供篩選
        $userModel = new \App\Models\User();
        $users = $userModel->where('status', 1);
        
        return $this->view('admin/action-logs/index', [
            'title' => '操作紀錄',
            'logs' => $logs['data'],
            'pagination' => $logs['pagination'],
            'filters' => $filters,
            'users' => $users
        ], 'admin');
    }
    
    /**
     * 檢視操作紀錄詳情
     */
    public function show($id)
    {
        $log = $this->actionLogModel->findWithUser($id);
        
        if (!$log) {
            return $this->redirect('/admin/action-logs');
        }
        
        // 解析 JSON 資料
        if (!empty($log['request_data'])) {
            $log['request_data'] = json_decode($log['request_data'], true);
        }
        
        return $this->view('admin/action-logs/show', [
            'title' => '操作紀錄詳情',
            'log' => $log
        ], 'admin');
    }
    
    /**
     * 匯出操作紀錄
     */
    public function export()
    {
        // 篩選條件
        $filters = [
            'user_id' => $_GET['user_id'] ?? '',
            'controller' => $_GET['controller'] ?? '',
            'action' => $_GET['action'] ?? '',
            'date_from' => $_GET['date_from'] ?? '',
            'date_to' => $_GET['date_to'] ?? ''
        ];
        
        $conditions = [];
        $params = [];
        
        if (!empty($filters['user_id'])) {
            $conditions[] = "user_id = ?";
            $params[] = (int)$filters['user_id'];
        }
        
        if (!empty($filters['controller'])) {
            $conditions[] = "controller LIKE ?";
            $params[] = "%{$filters['controller']}%";
        }
        
        if (!empty($filters['action'])) {
            $conditions[] = "action LIKE ?";
            $params[] = "%{$filters['action']}%";
        }
        
        if (!empty($filters['date_from'])) {
            $conditions[] = "created_at >= ?";
            $params[] = $filters['date_from'] . ' 00:00:00';
        }
        
        if (!empty($filters['date_to'])) {
            $conditions[] = "created_at <= ?";
            $params[] = $filters['date_to'] . ' 23:59:59';
        }
        
        $logs = $this->actionLogModel->getWithUser($conditions, $params, 10000);
        
        // 產生 CSV
        $filename = 'action_logs_' . date('Y-m-d_His') . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // BOM for Excel
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
        
        // Header
        fputcsv($output, ['ID', '使用者', '控制器', '方法', 'IP 位址', '使用者代理', '建立時間']);
        
        // Data
        foreach ($logs as $log) {
            fputcsv($output, [
                $log['id'],
                $log['username'] ?? 'N/A',
                $log['controller'],
                $log['action'],
                $log['ip_address'],
                $log['user_agent'],
                $log['created_at']
            ]);
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * 清理舊紀錄 (僅限 Admin)
     */
    public function cleanup()
    {
        if (!$this->verifyCsrf()) {
            return $this->json(['success' => false, 'message' => '安全驗證失敗']);
        }
        
        $days = (int)$this->input('days', 90);
        
        if ($days < 30) {
            return $this->json(['success' => false, 'message' => '保留天數不能少於 30 天']);
        }
        
        $cutoffDate = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        
        $deleted = $this->actionLogModel->deleteOlderThan($cutoffDate);
        
        return $this->json([
            'success' => true,
            'message' => "已刪除 {$deleted} 筆舊紀錄"
        ]);
    }
}
