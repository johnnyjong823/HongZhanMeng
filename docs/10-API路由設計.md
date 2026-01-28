# API 路由設計

## 路由概述

所有網址皆使用小寫，符合 RESTful 風格。

---

## 路由設定檔

**檔案位置**: `config/routes.php`

```php
<?php
/**
 * 路由設定
 * 
 * 格式:
 * [
 *     'method' => 'GET|POST|PUT|DELETE|ANY',
 *     'path' => '/path/to/route/{param}',
 *     'controller' => 'ControllerName',
 *     'action' => 'methodName',
 *     'middleware' => ['MiddlewareClass', ...]
 * ]
 */

use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;

return [
    // ========================================
    // 前台路由 (不需登入)
    // ========================================
    
    // 首頁
    [
        'method' => 'GET',
        'path' => '/',
        'controller' => 'Frontend\HomeController',
        'action' => 'index',
        'middleware' => []
    ],
    
    // 其他前台頁面...
    // [
    //     'method' => 'GET',
    //     'path' => '/about',
    //     'controller' => 'Frontend\PageController',
    //     'action' => 'about',
    //     'middleware' => []
    // ],
    
    // ========================================
    // 帳號相關路由
    // ========================================
    
    // 登入頁面
    [
        'method' => 'GET',
        'path' => '/account/login',
        'controller' => 'AccountController',
        'action' => 'login',
        'middleware' => []
    ],
    
    // 登入處理
    [
        'method' => 'POST',
        'path' => '/account/login',
        'controller' => 'AccountController',
        'action' => 'login',
        'middleware' => []
    ],
    
    // 登出
    [
        'method' => 'GET',
        'path' => '/account/logout',
        'controller' => 'AccountController',
        'action' => 'logout',
        'middleware' => [AuthMiddleware::class]
    ],
    
    // 忘記密碼頁面
    [
        'method' => 'GET',
        'path' => '/account/forgot-password',
        'controller' => 'AccountController',
        'action' => 'forgotPassword',
        'middleware' => []
    ],
    
    // 忘記密碼處理
    [
        'method' => 'POST',
        'path' => '/account/forgot-password',
        'controller' => 'AccountController',
        'action' => 'forgotPassword',
        'middleware' => []
    ],
    
    // 重設密碼頁面
    [
        'method' => 'GET',
        'path' => '/account/reset-password',
        'controller' => 'AccountController',
        'action' => 'resetPassword',
        'middleware' => []
    ],
    
    // 重設密碼處理
    [
        'method' => 'POST',
        'path' => '/account/reset-password',
        'controller' => 'AccountController',
        'action' => 'resetPassword',
        'middleware' => []
    ],
    
    // 修改密碼頁面 (需登入)
    [
        'method' => 'GET',
        'path' => '/account/change-password',
        'controller' => 'AccountController',
        'action' => 'changePassword',
        'middleware' => [AuthMiddleware::class]
    ],
    
    // 修改密碼處理
    [
        'method' => 'POST',
        'path' => '/account/change-password',
        'controller' => 'AccountController',
        'action' => 'changePassword',
        'middleware' => [AuthMiddleware::class]
    ],
    
    // ========================================
    // 後台路由 (需登入)
    // ========================================
    
    // 控制台
    [
        'method' => 'GET',
        'path' => '/admin/dashboard',
        'controller' => 'Backend\DashboardController',
        'action' => 'index',
        'middleware' => [AuthMiddleware::class]
    ],
    
    // ----------------------------------------
    // 帳號管理 (host 以上)
    // ----------------------------------------
    
    // 使用者列表
    [
        'method' => 'GET',
        'path' => '/admin/users',
        'controller' => 'Backend\UserController',
        'action' => 'index',
        'middleware' => [AuthMiddleware::class]
    ],
    
    // 新增使用者頁面
    [
        'method' => 'GET',
        'path' => '/admin/users/create',
        'controller' => 'Backend\UserController',
        'action' => 'create',
        'middleware' => [AuthMiddleware::class]
    ],
    
    // 新增使用者處理
    [
        'method' => 'POST',
        'path' => '/admin/users',
        'controller' => 'Backend\UserController',
        'action' => 'store',
        'middleware' => [AuthMiddleware::class]
    ],
    
    // 編輯使用者頁面
    [
        'method' => 'GET',
        'path' => '/admin/users/{id}/edit',
        'controller' => 'Backend\UserController',
        'action' => 'edit',
        'middleware' => [AuthMiddleware::class]
    ],
    
    // 更新使用者
    [
        'method' => 'POST',
        'path' => '/admin/users/{id}',
        'controller' => 'Backend\UserController',
        'action' => 'update',
        'middleware' => [AuthMiddleware::class]
    ],
    
    // 刪除使用者
    [
        'method' => 'POST',
        'path' => '/admin/users/{id}/delete',
        'controller' => 'Backend\UserController',
        'action' => 'destroy',
        'middleware' => [AuthMiddleware::class]
    ],
    
    // ----------------------------------------
    // 角色管理 (admin 專用)
    // ----------------------------------------
    
    // 角色列表
    [
        'method' => 'GET',
        'path' => '/admin/roles',
        'controller' => 'Backend\RoleController',
        'action' => 'index',
        'middleware' => [AuthMiddleware::class]
    ],
    
    // 新增角色頁面
    [
        'method' => 'GET',
        'path' => '/admin/roles/create',
        'controller' => 'Backend\RoleController',
        'action' => 'create',
        'middleware' => [AuthMiddleware::class]
    ],
    
    // 新增角色處理
    [
        'method' => 'POST',
        'path' => '/admin/roles',
        'controller' => 'Backend\RoleController',
        'action' => 'store',
        'middleware' => [AuthMiddleware::class]
    ],
    
    // 編輯角色頁面
    [
        'method' => 'GET',
        'path' => '/admin/roles/{id}/edit',
        'controller' => 'Backend\RoleController',
        'action' => 'edit',
        'middleware' => [AuthMiddleware::class]
    ],
    
    // 更新角色
    [
        'method' => 'POST',
        'path' => '/admin/roles/{id}',
        'controller' => 'Backend\RoleController',
        'action' => 'update',
        'middleware' => [AuthMiddleware::class]
    ],
    
    // 角色權限設定頁面
    [
        'method' => 'GET',
        'path' => '/admin/roles/{id}/permissions',
        'controller' => 'Backend\RoleController',
        'action' => 'permissions',
        'middleware' => [AuthMiddleware::class]
    ],
    
    // 更新角色權限
    [
        'method' => 'POST',
        'path' => '/admin/roles/{id}/permissions',
        'controller' => 'Backend\RoleController',
        'action' => 'updatePermissions',
        'middleware' => [AuthMiddleware::class]
    ],
    
    // ----------------------------------------
    // 功能管理 (admin 專用)
    // ----------------------------------------
    
    // 功能列表
    [
        'method' => 'GET',
        'path' => '/admin/functions',
        'controller' => 'Backend\FunctionController',
        'action' => 'index',
        'middleware' => [AuthMiddleware::class]
    ],
    
    // 新增功能頁面
    [
        'method' => 'GET',
        'path' => '/admin/functions/create',
        'controller' => 'Backend\FunctionController',
        'action' => 'create',
        'middleware' => [AuthMiddleware::class]
    ],
    
    // 新增功能處理
    [
        'method' => 'POST',
        'path' => '/admin/functions',
        'controller' => 'Backend\FunctionController',
        'action' => 'store',
        'middleware' => [AuthMiddleware::class]
    ],
    
    // 編輯功能頁面
    [
        'method' => 'GET',
        'path' => '/admin/functions/{id}/edit',
        'controller' => 'Backend\FunctionController',
        'action' => 'edit',
        'middleware' => [AuthMiddleware::class]
    ],
    
    // 更新功能
    [
        'method' => 'POST',
        'path' => '/admin/functions/{id}',
        'controller' => 'Backend\FunctionController',
        'action' => 'update',
        'middleware' => [AuthMiddleware::class]
    ],
    
    // ----------------------------------------
    // 操作紀錄 (host 以上)
    // ----------------------------------------
    
    // 操作紀錄列表
    [
        'method' => 'GET',
        'path' => '/admin/action-logs',
        'controller' => 'Backend\ActionLogController',
        'action' => 'index',
        'middleware' => [AuthMiddleware::class]
    ],
    
    // 操作紀錄詳情
    [
        'method' => 'GET',
        'path' => '/admin/action-logs/{id}',
        'controller' => 'Backend\ActionLogController',
        'action' => 'show',
        'middleware' => [AuthMiddleware::class]
    ],
    
    // 匯出操作紀錄
    [
        'method' => 'GET',
        'path' => '/admin/action-logs/export',
        'controller' => 'Backend\ActionLogController',
        'action' => 'export',
        'middleware' => [AuthMiddleware::class]
    ],
    
    // ----------------------------------------
    // 登入紀錄 (host 以上)
    // ----------------------------------------
    
    // 登入紀錄列表
    [
        'method' => 'GET',
        'path' => '/admin/login-logs',
        'controller' => 'Backend\LoginLogController',
        'action' => 'index',
        'middleware' => [AuthMiddleware::class]
    ],
];
```

---

## 路由總覽表

### 前台路由

| 方法 | 網址 | 控制器 | 說明 |
|------|------|--------|------|
| GET | `/` | HomeController@index | 首頁 |

### 帳號相關

| 方法 | 網址 | 控制器 | 說明 |
|------|------|--------|------|
| GET | `/account/login` | AccountController@login | 登入頁面 |
| POST | `/account/login` | AccountController@login | 登入處理 |
| GET | `/account/logout` | AccountController@logout | 登出 |
| GET | `/account/forgot-password` | AccountController@forgotPassword | 忘記密碼頁面 |
| POST | `/account/forgot-password` | AccountController@forgotPassword | 忘記密碼處理 |
| GET | `/account/reset-password` | AccountController@resetPassword | 重設密碼頁面 |
| POST | `/account/reset-password` | AccountController@resetPassword | 重設密碼處理 |
| GET | `/account/change-password` | AccountController@changePassword | 修改密碼頁面 |
| POST | `/account/change-password` | AccountController@changePassword | 修改密碼處理 |

### 後台路由

| 方法 | 網址 | 控制器 | 權限 | 說明 |
|------|------|--------|------|------|
| GET | `/admin/dashboard` | DashboardController@index | 全部 | 控制台 |
| GET | `/admin/users` | UserController@index | host+ | 使用者列表 |
| GET | `/admin/users/create` | UserController@create | host+ | 新增使用者 |
| POST | `/admin/users` | UserController@store | host+ | 儲存使用者 |
| GET | `/admin/users/{id}/edit` | UserController@edit | host+ | 編輯使用者 |
| POST | `/admin/users/{id}` | UserController@update | host+ | 更新使用者 |
| POST | `/admin/users/{id}/delete` | UserController@destroy | host+ | 刪除使用者 |
| GET | `/admin/roles` | RoleController@index | admin | 角色列表 |
| GET | `/admin/roles/create` | RoleController@create | admin | 新增角色 |
| POST | `/admin/roles` | RoleController@store | admin | 儲存角色 |
| GET | `/admin/roles/{id}/edit` | RoleController@edit | admin | 編輯角色 |
| POST | `/admin/roles/{id}` | RoleController@update | admin | 更新角色 |
| GET | `/admin/roles/{id}/permissions` | RoleController@permissions | admin | 角色權限 |
| POST | `/admin/roles/{id}/permissions` | RoleController@updatePermissions | admin | 更新權限 |
| GET | `/admin/functions` | FunctionController@index | admin | 功能列表 |
| GET | `/admin/functions/create` | FunctionController@create | admin | 新增功能 |
| POST | `/admin/functions` | FunctionController@store | admin | 儲存功能 |
| GET | `/admin/functions/{id}/edit` | FunctionController@edit | admin | 編輯功能 |
| POST | `/admin/functions/{id}` | FunctionController@update | admin | 更新功能 |
| GET | `/admin/action-logs` | ActionLogController@index | host+ | 操作紀錄 |
| GET | `/admin/action-logs/{id}` | ActionLogController@show | host+ | 紀錄詳情 |
| GET | `/admin/action-logs/export` | ActionLogController@export | host+ | 匯出紀錄 |
| GET | `/admin/login-logs` | LoginLogController@index | host+ | 登入紀錄 |

---

## 路由參數說明

### 動態參數

在路由中使用 `{param}` 表示動態參數：

```php
'/admin/users/{id}/edit'  // {id} 會傳入控制器方法
```

控制器接收方式：

```php
public function edit($id)
{
    $user = $this->userModel->find($id);
    // ...
}
```

### Query String 參數

使用 `$_GET` 取得：

```php
// 網址: /admin/users?page=2&status=1

public function index()
{
    $page = $_GET['page'] ?? 1;
    $status = $_GET['status'] ?? null;
}
```

---

## URL 輔助函式

**檔案位置**: `app/Helpers/functions.php`

```php
<?php
/**
 * 產生網址
 */
function url($path = '')
{
    $baseUrl = rtrim($_ENV['APP_URL'] ?? '', '/');
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
 * 重新導向
 */
function redirect($url)
{
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
    return $currentPath === $path;
}

/**
 * 判斷網址是否以某路徑開頭
 */
function is_active_url($path)
{
    $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    return strpos($currentPath, $path) === 0;
}
```

---

## 使用範例

### 視圖中使用

```php
<!-- 連結到首頁 -->
<a href="<?= url('/') ?>">首頁</a>

<!-- 連結到後台 -->
<a href="<?= admin_url('dashboard') ?>">控制台</a>

<!-- 連結到使用者編輯頁面 -->
<a href="<?= admin_url('users/' . $user['id'] . '/edit') ?>">編輯</a>

<!-- 載入 CSS -->
<link href="<?= asset('css/style.css') ?>" rel="stylesheet">

<!-- 選單高亮 -->
<li class="<?= is_active_url('/admin/users') ? 'active' : '' ?>">
    <a href="<?= admin_url('users') ?>">使用者管理</a>
</li>
```

### 控制器中使用

```php
// 重新導向到列表頁
$this->redirect(admin_url('users'));

// 返回上一頁
back();
```
