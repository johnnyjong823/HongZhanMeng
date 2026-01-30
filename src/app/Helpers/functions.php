<?php
/**
 * 全域輔助函式
 */

/**
 * 取得設定值
 */
function config($key = null, $default = null)
{
    static $config = null;
    
    if ($config === null) {
        $config = require ROOT_PATH . '/config/app.php';
    }
    
    if ($key === null) {
        return $config;
    }
    
    return $config[$key] ?? $default;
}

/**
 * 取得基礎路徑 (用於子資料夾部署)
 */
function base_path()
{
    static $basePath = null;
    
    if ($basePath === null) {
        $basePath = rtrim(config('base_path', '/'), '/');
        if ($basePath === '') {
            $basePath = '';
        }
    }
    
    return $basePath;
}

/**
 * 產生網址
 */
function url($path = '')
{
    $basePath = base_path();
    $path = '/' . ltrim($path, '/');
    
    // 如果有 base_path，加上前綴
    if ($basePath !== '' && $basePath !== '/') {
        return $basePath . $path;
    }
    
    return $path;
}

/**
 * 產生完整網址 (含 domain)
 */
function full_url($path = '')
{
    $baseUrl = rtrim(config('url', 'http://localhost:8801'), '/');
    return $baseUrl . '/' . ltrim($path, '/');
}

/**
 * 產生後台網址
 */
function admin_url($path = '')
{
    return url('admin/' . ltrim($path, '/'));
}

/**
 * 產生資源網址
 */
function asset($path)
{
    return url('assets/' . ltrim($path, '/'));
}

/**
 * 產生上傳檔案網址
 */
function upload_url($path)
{
    return url('uploads/' . ltrim($path, '/'));
}

/**
 * 重新導向
 */
function redirect($url)
{
    // 自動加上 base_path (如果是相對路徑)
    if (strpos($url, '/') === 0 && strpos($url, '//') !== 0) {
        $url = url($url);
    }
    
    header('Location: ' . $url);
    exit;
}

/**
 * 返回上一頁
 */
function back()
{
    $referer = $_SERVER['HTTP_REFERER'] ?? '/';
    redirect($referer);
}

/**
 * 取得目前網址
 */
function current_url()
{
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    return $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

/**
 * 判斷是否為目前網址
 */
function is_current_url($path)
{
    $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    return strtolower($currentPath) === strtolower($path);
}

/**
 * 判斷網址是否以某路徑開頭
 */
function is_active_url($path)
{
    $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    return strpos(strtolower($currentPath), strtolower($path)) === 0;
}

/**
 * 檢查是否已登入
 */
function is_logged_in()
{
    return isset($_SESSION['user']) && !empty($_SESSION['user']['id']);
}

/**
 * 取得目前使用者
 */
function current_user()
{
    return $_SESSION['user'] ?? null;
}

/**
 * 取得目前使用者 ID
 */
function current_user_id()
{
    return $_SESSION['user']['id'] ?? null;
}

/**
 * 檢查權限層級
 */
function has_level($requiredLevel)
{
    $userLevel = $_SESSION['user']['level'] ?? 999;
    return $userLevel <= $requiredLevel;
}

/**
 * 檢查功能權限
 */
function has_permission($functionCode, $action = 'view')
{
    // admin 擁有所有權限
    if (has_level(1)) {
        return true;
    }
    
    $permissions = $_SESSION['permissions'] ?? [];
    
    if (!isset($permissions[$functionCode])) {
        return false;
    }
    
    $actionMap = [
        'view' => 'can_view',
        'create' => 'can_create',
        'edit' => 'can_edit',
        'delete' => 'can_delete'
    ];
    
    $permKey = $actionMap[$action] ?? 'can_view';
    
    return $permissions[$functionCode][$permKey] ?? false;
}

/**
 * 是否為管理者
 */
function is_admin()
{
    return has_level(1);
}

/**
 * 是否為使用者管理者
 */
function is_host()
{
    return has_level(2);
}

/**
 * 取得選單
 */
function get_menu()
{
    return $_SESSION['menu'] ?? [];
}

/**
 * HTML 轉義
 */
function e($string)
{
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * 格式化日期時間
 */
function format_datetime($datetime, $format = 'Y-m-d H:i:s')
{
    if (empty($datetime)) {
        return '';
    }
    
    return date($format, strtotime($datetime));
}

/**
 * 格式化日期
 */
function format_date($date, $format = 'Y-m-d')
{
    return format_datetime($date, $format);
}

/**
 * 產生分頁 HTML
 */
function pagination($data, $baseUrl)
{
    if ($data['last_page'] <= 1) {
        return '';
    }
    
    $html = '<nav><ul class="pagination justify-content-center">';
    
    // 上一頁
    if ($data['current_page'] > 1) {
        $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . ($data['current_page'] - 1) . '">上一頁</a></li>';
    } else {
        $html .= '<li class="page-item disabled"><span class="page-link">上一頁</span></li>';
    }
    
    // 頁碼
    $start = max(1, $data['current_page'] - 2);
    $end = min($data['last_page'], $data['current_page'] + 2);
    
    if ($start > 1) {
        $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=1">1</a></li>';
        if ($start > 2) {
            $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
    }
    
    for ($i = $start; $i <= $end; $i++) {
        if ($i == $data['current_page']) {
            $html .= '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
        } else {
            $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . $i . '">' . $i . '</a></li>';
        }
    }
    
    if ($end < $data['last_page']) {
        if ($end < $data['last_page'] - 1) {
            $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
        $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . $data['last_page'] . '">' . $data['last_page'] . '</a></li>';
    }
    
    // 下一頁
    if ($data['current_page'] < $data['last_page']) {
        $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . ($data['current_page'] + 1) . '">下一頁</a></li>';
    } else {
        $html .= '<li class="page-item disabled"><span class="page-link">下一頁</span></li>';
    }
    
    $html .= '</ul></nav>';
    
    return $html;
}

/**
 * 取得客戶端 IP
 */
function get_client_ip()
{
    $headers = [
        'HTTP_CF_CONNECTING_IP',     // Cloudflare
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
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

/**
 * 產生 CSRF Token 隱藏欄位
 */
function csrf_field()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return '<input type="hidden" name="_token" value="' . $_SESSION['csrf_token'] . '">';
}

/**
 * 取得 CSRF Token
 */
function csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}

/**
 * 驗證 CSRF Token
 */
function verify_csrf_token($token)
{
    if (empty($_SESSION['csrf_token'])) {
        return false;
    }
    
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * 狀態標籤
 */
function status_badge($status)
{
    if ($status == 1) {
        return '<span class="badge bg-success">啟用</span>';
    } else {
        return '<span class="badge bg-secondary">停用</span>';
    }
}

/**
 * 安全輸出 HTML 內容
 * 過濾危險標籤（如 script, iframe, object 等）
 * 保留安全的格式化標籤
 * 
 * @param string $html HTML 內容
 * @return string 過濾後的 HTML
 */
function safe_html($html)
{
    if (empty($html)) {
        return '';
    }
    
    // 允許的 HTML 標籤
    $allowedTags = '<p><br><strong><b><em><i><u><s><strike><del><ins>' .
                   '<h1><h2><h3><h4><h5><h6><a><ul><ol><li><blockquote>' .
                   '<pre><code><hr><table><thead><tbody><tfoot><tr><th><td>' .
                   '<img><figure><figcaption><div><span><sub><sup>' .
                   '<address><article><aside><details><summary><section><style>';
    
    // 移除不允許的標籤
    $html = strip_tags($html, $allowedTags);
    
    // 移除 JavaScript 事件屬性（如 onclick, onerror 等）
    $html = preg_replace('/\s*on\w+\s*=\s*(["\'])[^"\']*\1/i', '', $html);
    $html = preg_replace('/\s*on\w+\s*=\s*[^\s>]*/i', '', $html);
    
    // 移除 javascript: 協議
    $html = preg_replace('/href\s*=\s*(["\'])\s*javascript:[^"\']*\1/i', 'href=$1#$1', $html);
    $html = preg_replace('/src\s*=\s*(["\'])\s*javascript:[^"\']*\1/i', 'src=$1#$1', $html);
    
    // 移除 data: 協議（在 src 中，可用於 XSS）
    $html = preg_replace('/src\s*=\s*(["\'])\s*data:[^"\']*\1/i', 'src=$1#$1', $html);
    
    // 移除 style 屬性中的 expression() 和 url() 可能的危險內容
    $html = preg_replace('/style\s*=\s*(["\'])[^"\']*expression\s*\([^)]*\)[^"\']*\1/i', '', $html);
    
    return $html;
}
