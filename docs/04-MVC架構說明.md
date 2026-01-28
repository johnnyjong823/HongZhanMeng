# MVC 架構說明

## PHP MVC 框架核心

本專案採用自建輕量級 MVC 框架，適合 cPanel 環境部署，不依賴 Composer。

---

## 核心元件

### 1. 自動載入器 (Autoloader)

**檔案位置**: `core/Autoloader.php`

```php
<?php
/**
 * 自動載入器
 */

spl_autoload_register(function ($class) {
    // 命名空間對應目錄
    $namespaceMap = [
        'Core\\' => ROOT_PATH . '/core/',
        'App\\Controllers\\' => ROOT_PATH . '/app/Controllers/',
        'App\\Models\\' => ROOT_PATH . '/app/Models/',
        'App\\Middleware\\' => ROOT_PATH . '/app/Middleware/',
        'App\\Filters\\' => ROOT_PATH . '/app/Filters/',
        'App\\Services\\' => ROOT_PATH . '/app/Services/',
        'App\\Helpers\\' => ROOT_PATH . '/app/Helpers/',
    ];
    
    foreach ($namespaceMap as $namespace => $directory) {
        if (strpos($class, $namespace) === 0) {
            $relativeClass = substr($class, strlen($namespace));
            $file = $directory . str_replace('\\', '/', $relativeClass) . '.php';
            
            if (file_exists($file)) {
                require_once $file;
                return true;
            }
        }
    }
    
    return false;
});
```

---

### 2. 應用程式核心 (App)

**檔案位置**: `core/App.php`

```php
<?php
namespace Core;

class App
{
    protected $config;
    protected $router;
    
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->initSession();
        $this->initRouter();
    }
    
    /**
     * 初始化 Session
     */
    protected function initSession()
    {
        // Session 設定
        ini_set('session.cookie_httponly', 1);
        ini_set('session.use_strict_mode', 1);
        ini_set('session.cookie_samesite', 'Lax');
        
        // Session 有效時間 (60 分鐘)
        ini_set('session.gc_maxlifetime', 3600);
        session_set_cookie_params(3600);
        
        session_start();
        
        // 檢查 Session 是否過期
        $this->checkSessionTimeout();
    }
    
    /**
     * 檢查 Session 逾時
     */
    protected function checkSessionTimeout()
    {
        $timeout = 3600; // 60 分鐘
        
        if (isset($_SESSION['last_activity'])) {
            if (time() - $_SESSION['last_activity'] > $timeout) {
                // Session 過期，清除並重新導向
                session_unset();
                session_destroy();
                
                if ($this->isBackendRequest()) {
                    header('Location: /account/login?expired=1');
                    exit;
                }
            }
        }
        
        // 更新最後活動時間
        $_SESSION['last_activity'] = time();
    }
    
    /**
     * 判斷是否為後台請求
     */
    protected function isBackendRequest()
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        return strpos($uri, '/admin') === 0;
    }
    
    /**
     * 初始化路由器
     */
    protected function initRouter()
    {
        $this->router = new Router();
        $routes = require ROOT_PATH . '/config/routes.php';
        
        foreach ($routes as $route) {
            $this->router->addRoute(
                $route['method'],
                $route['path'],
                $route['controller'],
                $route['action'],
                $route['middleware'] ?? []
            );
        }
    }
    
    /**
     * 執行應用程式
     */
    public function run()
    {
        try {
            $this->router->dispatch();
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }
    
    /**
     * 例外處理
     */
    protected function handleException(\Exception $e)
    {
        $code = $e->getCode() ?: 500;
        http_response_code($code);
        
        if ($this->config['debug'] ?? false) {
            echo '<h1>Error: ' . $e->getMessage() . '</h1>';
            echo '<pre>' . $e->getTraceAsString() . '</pre>';
        } else {
            include ROOT_PATH . '/app/Views/errors/' . $code . '.php';
        }
    }
}
```

---

### 3. 路由器 (Router)

**檔案位置**: `core/Router.php`

```php
<?php
namespace Core;

class Router
{
    protected $routes = [];
    protected $params = [];
    
    /**
     * 新增路由
     */
    public function addRoute($method, $path, $controller, $action, $middleware = [])
    {
        // 將路由路徑轉換為正規表達式
        $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $path);
        $pattern = '#^' . $pattern . '$#';
        
        $this->routes[] = [
            'method' => strtoupper($method),
            'pattern' => $pattern,
            'path' => $path,
            'controller' => $controller,
            'action' => $action,
            'middleware' => $middleware
        ];
    }
    
    /**
     * 路由分發
     */
    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $this->getUri();
        
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method && $route['method'] !== 'ANY') {
                continue;
            }
            
            if (preg_match($route['pattern'], $uri, $matches)) {
                // 取得路由參數
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                
                // 執行中介層
                $this->runMiddleware($route['middleware']);
                
                // 呼叫控制器
                $this->callController($route['controller'], $route['action'], $params);
                return;
            }
        }
        
        // 找不到路由
        throw new \Exception('Page not found', 404);
    }
    
    /**
     * 取得請求 URI
     */
    protected function getUri()
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = strtolower(trim($uri, '/'));
        
        return '/' . $uri;
    }
    
    /**
     * 執行中介層
     */
    protected function runMiddleware(array $middleware)
    {
        foreach ($middleware as $middlewareClass) {
            $instance = new $middlewareClass();
            $instance->handle();
        }
    }
    
    /**
     * 呼叫控制器
     */
    protected function callController($controller, $action, $params)
    {
        $controllerClass = 'App\\Controllers\\' . $controller;
        
        if (!class_exists($controllerClass)) {
            throw new \Exception("Controller {$controller} not found", 500);
        }
        
        $instance = new $controllerClass();
        
        if (!method_exists($instance, $action)) {
            throw new \Exception("Action {$action} not found in {$controller}", 500);
        }
        
        call_user_func_array([$instance, $action], $params);
    }
}
```

---

### 4. 基礎控制器 (Controller)

**檔案位置**: `core/Controller.php`

```php
<?php
namespace Core;

use App\Filters\RecordActionFilter;

abstract class Controller
{
    protected $view;
    protected $request;
    
    public function __construct()
    {
        $this->view = new View();
        $this->request = new Request();
        
        // 記錄操作
        $this->recordAction();
    }
    
    /**
     * 記錄操作
     */
    protected function recordAction()
    {
        $filter = new RecordActionFilter();
        $filter->record(
            get_class($this),
            debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function'] ?? 'unknown'
        );
    }
    
    /**
     * 渲染視圖
     */
    protected function render($template, $data = [], $layout = null)
    {
        $this->view->render($template, $data, $layout);
    }
    
    /**
     * 重新導向
     */
    protected function redirect($url, $statusCode = 302)
    {
        header('Location: ' . $url, true, $statusCode);
        exit;
    }
    
    /**
     * 回傳 JSON
     */
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    /**
     * 取得目前登入使用者
     */
    protected function currentUser()
    {
        return $_SESSION['user'] ?? null;
    }
    
    /**
     * 檢查是否已登入
     */
    protected function isLoggedIn()
    {
        return isset($_SESSION['user']) && !empty($_SESSION['user']['id']);
    }
    
    /**
     * 檢查權限層級
     */
    protected function hasLevel($requiredLevel)
    {
        $user = $this->currentUser();
        if (!$user) return false;
        
        return ($user['level'] ?? 999) <= $requiredLevel;
    }
    
    /**
     * 檢查功能權限
     */
    protected function hasPermission($functionCode, $action = 'view')
    {
        $user = $this->currentUser();
        if (!$user) return false;
        
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
}
```

---

### 5. 基礎模型 (Model)

**檔案位置**: `core/Model.php`

```php
<?php
namespace Core;

abstract class Model
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * 取得所有資料
     */
    public function all($columns = ['*'])
    {
        $cols = implode(', ', $columns);
        $sql = "SELECT {$cols} FROM {$this->table}";
        return $this->db->query($sql)->fetchAll();
    }
    
    /**
     * 依 ID 查詢
     */
    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1";
        return $this->db->query($sql, ['id' => $id])->fetch();
    }
    
    /**
     * 依條件查詢
     */
    public function where($column, $value, $operator = '=')
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} {$operator} :value";
        return $this->db->query($sql, ['value' => $value])->fetchAll();
    }
    
    /**
     * 依條件查詢單筆
     */
    public function findBy($column, $value)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = :value LIMIT 1";
        return $this->db->query($sql, ['value' => $value])->fetch();
    }
    
    /**
     * 新增資料
     */
    public function create(array $data)
    {
        $data = $this->filterFillable($data);
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $this->db->query($sql, $data);
        
        return $this->db->lastInsertId();
    }
    
    /**
     * 更新資料
     */
    public function update($id, array $data)
    {
        $data = $this->filterFillable($data);
        $sets = [];
        
        foreach (array_keys($data) as $column) {
            $sets[] = "{$column} = :{$column}";
        }
        
        $setString = implode(', ', $sets);
        $data['id'] = $id;
        
        $sql = "UPDATE {$this->table} SET {$setString} WHERE {$this->primaryKey} = :id";
        return $this->db->query($sql, $data);
    }
    
    /**
     * 刪除資料
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        return $this->db->query($sql, ['id' => $id]);
    }
    
    /**
     * 過濾可填入欄位
     */
    protected function filterFillable(array $data)
    {
        if (empty($this->fillable)) {
            return $data;
        }
        
        return array_intersect_key($data, array_flip($this->fillable));
    }
    
    /**
     * 分頁查詢
     */
    public function paginate($page = 1, $perPage = 15, $conditions = [], $orderBy = 'id DESC')
    {
        $offset = ($page - 1) * $perPage;
        
        // 計算總數
        $countSql = "SELECT COUNT(*) as total FROM {$this->table}";
        $whereClauses = [];
        $params = [];
        
        foreach ($conditions as $column => $value) {
            $whereClauses[] = "{$column} = :{$column}";
            $params[$column] = $value;
        }
        
        if (!empty($whereClauses)) {
            $countSql .= ' WHERE ' . implode(' AND ', $whereClauses);
        }
        
        $total = $this->db->query($countSql, $params)->fetch()['total'];
        
        // 查詢資料
        $sql = "SELECT * FROM {$this->table}";
        
        if (!empty($whereClauses)) {
            $sql .= ' WHERE ' . implode(' AND ', $whereClauses);
        }
        
        $sql .= " ORDER BY {$orderBy} LIMIT {$perPage} OFFSET {$offset}";
        
        $data = $this->db->query($sql, $params)->fetchAll();
        
        return [
            'data' => $data,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage)
        ];
    }
}
```

---

### 6. 資料庫連接 (Database)

**檔案位置**: `core/Database.php`

```php
<?php
namespace Core;

class Database
{
    private static $instance = null;
    private $pdo;
    
    private function __construct()
    {
        $config = require ROOT_PATH . '/config/database.php';
        
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $config['host'],
            $config['port'],
            $config['database'],
            $config['charset']
        );
        
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        $this->pdo = new \PDO(
            $dsn,
            $config['username'],
            $config['password'],
            $options
        );
    }
    
    /**
     * 取得單例實體
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    /**
     * 執行查詢
     */
    public function query($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    /**
     * 取得最後插入 ID
     */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
    
    /**
     * 開始交易
     */
    public function beginTransaction()
    {
        return $this->pdo->beginTransaction();
    }
    
    /**
     * 提交交易
     */
    public function commit()
    {
        return $this->pdo->commit();
    }
    
    /**
     * 回滾交易
     */
    public function rollBack()
    {
        return $this->pdo->rollBack();
    }
    
    /**
     * 取得 PDO 實體
     */
    public function getPdo()
    {
        return $this->pdo;
    }
}
```

---

### 7. 視圖渲染器 (View)

**檔案位置**: `core/View.php`

```php
<?php
namespace Core;

class View
{
    protected $data = [];
    protected $layout = null;
    
    /**
     * 渲染視圖
     */
    public function render($template, $data = [], $layout = null)
    {
        $this->data = $data;
        $this->layout = $layout;
        
        // 取得視圖內容
        $content = $this->getViewContent($template);
        
        // 如果有版型，將內容嵌入版型
        if ($this->layout) {
            $this->data['content'] = $content;
            echo $this->getLayoutContent($this->layout);
        } else {
            echo $content;
        }
    }
    
    /**
     * 取得視圖內容
     */
    protected function getViewContent($template)
    {
        $viewPath = ROOT_PATH . '/app/Views/' . $template . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \Exception("View {$template} not found");
        }
        
        extract($this->data);
        
        ob_start();
        include $viewPath;
        return ob_get_clean();
    }
    
    /**
     * 取得版型內容
     */
    protected function getLayoutContent($layout)
    {
        $layoutPath = ROOT_PATH . '/app/Views/layouts/' . $layout . '.php';
        
        if (!file_exists($layoutPath)) {
            throw new \Exception("Layout {$layout} not found");
        }
        
        extract($this->data);
        
        ob_start();
        include $layoutPath;
        return ob_get_clean();
    }
    
    /**
     * 載入部分視圖
     */
    public static function partial($name, $data = [])
    {
        $path = ROOT_PATH . '/app/Views/' . $name . '.php';
        
        if (file_exists($path)) {
            extract($data);
            include $path;
        }
    }
    
    /**
     * 輸出 HTML 轉義字串
     */
    public static function e($string)
    {
        return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
    }
}
```
