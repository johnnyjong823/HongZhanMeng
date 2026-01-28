<?php
namespace App\Filters;

use App\Models\ActionLog;

/**
 * 操作紀錄過濾器
 * 
 * 用於記錄 Controller 的所有操作
 * 相當於 .NET 的 ActionFilterAttribute
 */
class RecordActionFilter
{
    protected $actionLogModel;
    
    /**
     * 不記錄的控制器/方法
     */
    protected $excludeActions = [
        'AccountController' => ['login', 'doLogin', 'logout'],
    ];
    
    /**
     * 敏感欄位 (不記錄內容)
     */
    protected $sensitiveFields = [
        'password',
        'password_confirmation',
        'current_password',
        'new_password',
        '_token'
    ];
    
    public function __construct()
    {
        $this->actionLogModel = new ActionLog();
    }
    
    /**
     * 記錄操作
     */
    public function record($controller, $method, $action = null, $description = null)
    {
        // 取得控制器簡短名稱
        $controllerName = $this->getShortControllerName($controller);
        
        // 檢查是否排除
        if ($this->isExcluded($controllerName, $method)) {
            return;
        }
        
        // 判斷操作類型
        if ($action === null) {
            $action = $this->determineAction($method);
        }
        
        // 取得請求資料
        $requestData = $this->getFilteredRequestData();
        
        // 建立紀錄
        $data = [
            'user_id' => $_SESSION['user']['id'] ?? null,
            'username' => $_SESSION['user']['username'] ?? null,
            'action' => $action,
            'controller' => $controllerName,
            'method' => $method,
            'url' => $_SERVER['REQUEST_URI'] ?? '',
            'http_method' => $_SERVER['REQUEST_METHOD'] ?? 'GET',
            'ip_address' => $this->getClientIp(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'request_data' => !empty($requestData) ? json_encode($requestData, JSON_UNESCAPED_UNICODE) : null,
            'description' => $description
        ];
        
        try {
            $this->actionLogModel->create($data);
        } catch (\Exception $e) {
            // 記錄失敗不影響主要流程
            error_log('RecordActionFilter error: ' . $e->getMessage());
        }
    }
    
    /**
     * 記錄自訂操作
     */
    public function recordCustom($action, $description, $extraData = [])
    {
        $data = [
            'user_id' => $_SESSION['user']['id'] ?? null,
            'username' => $_SESSION['user']['username'] ?? null,
            'action' => $action,
            'controller' => $extraData['controller'] ?? 'Custom',
            'method' => $extraData['method'] ?? 'custom',
            'url' => $_SERVER['REQUEST_URI'] ?? '',
            'http_method' => $_SERVER['REQUEST_METHOD'] ?? 'GET',
            'ip_address' => $this->getClientIp(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'request_data' => !empty($extraData) ? json_encode($extraData, JSON_UNESCAPED_UNICODE) : null,
            'description' => $description
        ];
        
        try {
            $this->actionLogModel->create($data);
        } catch (\Exception $e) {
            error_log('RecordActionFilter error: ' . $e->getMessage());
        }
    }
    
    /**
     * 取得控制器簡短名稱
     */
    protected function getShortControllerName($controller)
    {
        $parts = explode('\\', $controller);
        return end($parts);
    }
    
    /**
     * 判斷操作類型
     */
    protected function determineAction($method)
    {
        $method = strtolower($method);
        
        // 檢視操作
        if (in_array($method, ['index', 'show', 'view', 'list', 'get', 'detail', 'edit', 'create', 'permissions'])) {
            return 'view';
        }
        
        // 新增操作
        if (in_array($method, ['store', 'add', 'insert', 'dostore'])) {
            return 'create';
        }
        
        // 編輯操作
        if (in_array($method, ['update', 'modify', 'save', 'doupdate', 'updatepermissions'])) {
            return 'update';
        }
        
        // 刪除操作
        if (in_array($method, ['delete', 'destroy', 'remove'])) {
            return 'delete';
        }
        
        return 'other';
    }
    
    /**
     * 檢查是否排除記錄
     */
    protected function isExcluded($controller, $method)
    {
        if (isset($this->excludeActions[$controller])) {
            return in_array($method, $this->excludeActions[$controller]);
        }
        
        return false;
    }
    
    /**
     * 取得過濾後的請求資料
     */
    protected function getFilteredRequestData()
    {
        $data = [];
        
        // GET 參數
        if (!empty($_GET)) {
            $data['query'] = $this->filterSensitive($_GET);
        }
        
        // POST 參數
        if (!empty($_POST)) {
            $data['body'] = $this->filterSensitive($_POST);
        }
        
        return $data;
    }
    
    /**
     * 過濾敏感欄位
     */
    protected function filterSensitive(array $data)
    {
        foreach ($data as $key => $value) {
            if (in_array(strtolower($key), $this->sensitiveFields)) {
                $data[$key] = '***';
            } elseif (is_array($value)) {
                $data[$key] = $this->filterSensitive($value);
            }
        }
        
        return $data;
    }
    
    /**
     * 取得客戶端 IP
     */
    protected function getClientIp()
    {
        $headers = [
            'HTTP_CF_CONNECTING_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'REMOTE_ADDR'
        ];
        
        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ips = explode(',', $_SERVER[$header]);
                $ip = trim($ips[0]);
                
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }
        
        return '0.0.0.0';
    }
}
