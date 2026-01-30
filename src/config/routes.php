<?php
/**
 * 路由設定
 */

use App\Middleware\AuthMiddleware;
use App\Middleware\AdminMiddleware;

return [
    // ========================================
    // 前台路由 (不需登入)
    // ========================================
    
    [
        'method' => 'GET',
        'path' => '/',
        'controller' => 'HomeController',
        'action' => 'index',
        'middleware' => []
    ],
    [
        'method' => 'GET',
        'path' => '/about',
        'controller' => 'HomeController',
        'action' => 'about',
        'middleware' => []
    ],
    [
        'method' => 'GET',
        'path' => '/about/hongzhanmeng',
        'controller' => 'HomeController',
        'action' => 'aboutHongZhanMeng',
        'middleware' => []
    ],
    [
        'method' => 'GET',
        'path' => '/about/cibes',
        'controller' => 'HomeController',
        'action' => 'aboutCibes',
        'middleware' => []
    ],
    [
        'method' => 'GET',
        'path' => '/contact',
        'controller' => 'HomeController',
        'action' => 'contact',
        'middleware' => []
    ],
    
    // ========================================
    // 產品介紹路由
    // ========================================
    [
        'method' => 'GET',
        'path' => '/products/ascenda',
        'controller' => 'HomeController',
        'action' => 'productsAscenda',
        'middleware' => []
    ],
    [
        'method' => 'GET',
        'path' => '/products/dimensions',
        'controller' => 'HomeController',
        'action' => 'productsDimensions',
        'middleware' => []
    ],
    [
        'method' => 'GET',
        'path' => '/products/specs',
        'controller' => 'HomeController',
        'action' => 'productsSpecs',
        'middleware' => []
    ],
    [
        'method' => 'GET',
        'path' => '/products/faq',
        'controller' => 'HomeController',
        'action' => 'productsFaq',
        'middleware' => []
    ],
    
    // ========================================
    // 知識分享路由
    // ========================================
    [
        'method' => 'GET',
        'path' => '/knowledge',
        'controller' => 'HomeController',
        'action' => 'knowledge',
        'middleware' => []
    ],
    [
        'method' => 'GET',
        'path' => '/knowledge/load-more',
        'controller' => 'HomeController',
        'action' => 'knowledgeLoadMore',
        'middleware' => []
    ],
    [
        'method' => 'GET',
        'path' => '/knowledge/{id}',
        'controller' => 'HomeController',
        'action' => 'knowledgeDetail',
        'middleware' => []
    ],
    [
        'method' => 'GET',
        'path' => '/contact-us',
        'controller' => 'HomeController',
        'action' => 'contactPage',
        'middleware' => []
    ],
    
    // ========================================
    // 帳號相關路由
    // ========================================
    
    [
        'method' => 'GET',
        'path' => '/account/login',
        'controller' => 'AccountController',
        'action' => 'login',
        'middleware' => []
    ],
    [
        'method' => 'POST',
        'path' => '/account/do-login',
        'controller' => 'AccountController',
        'action' => 'doLogin',
        'middleware' => []
    ],
    [
        'method' => 'GET',
        'path' => '/account/logout',
        'controller' => 'AccountController',
        'action' => 'logout',
        'middleware' => []
    ],
    [
        'method' => 'GET',
        'path' => '/account/forgot-password',
        'controller' => 'AccountController',
        'action' => 'forgotPassword',
        'middleware' => []
    ],
    [
        'method' => 'POST',
        'path' => '/account/do-forgot-password',
        'controller' => 'AccountController',
        'action' => 'doForgotPassword',
        'middleware' => []
    ],
    [
        'method' => 'GET',
        'path' => '/account/reset-password',
        'controller' => 'AccountController',
        'action' => 'resetPassword',
        'middleware' => []
    ],
    [
        'method' => 'POST',
        'path' => '/account/do-reset-password',
        'controller' => 'AccountController',
        'action' => 'doResetPassword',
        'middleware' => []
    ],
    
    // ========================================
    // 後台路由 (需登入)
    // ========================================
    
    // 控制台
    [
        'method' => 'GET',
        'path' => '/admin',
        'controller' => 'DashboardController',
        'action' => 'index',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'GET',
        'path' => '/admin/dashboard',
        'controller' => 'DashboardController',
        'action' => 'index',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'GET',
        'path' => '/admin/dashboard/system-info',
        'controller' => 'DashboardController',
        'action' => 'systemInfo',
        'middleware' => [AuthMiddleware::class]
    ],
    
    // 帳號相關 (後台)
    [
        'method' => 'GET',
        'path' => '/admin/account/profile',
        'controller' => 'AccountController',
        'action' => 'profile',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'POST',
        'path' => '/admin/account/update-profile',
        'controller' => 'AccountController',
        'action' => 'updateProfile',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'GET',
        'path' => '/admin/account/change-password',
        'controller' => 'AccountController',
        'action' => 'changePassword',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'POST',
        'path' => '/admin/account/do-change-password',
        'controller' => 'AccountController',
        'action' => 'doChangePassword',
        'middleware' => [AuthMiddleware::class]
    ],
    
    // ----------------------------------------
    // 使用者管理
    // ----------------------------------------
    [
        'method' => 'GET',
        'path' => '/admin/users',
        'controller' => 'UserController',
        'action' => 'index',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'GET',
        'path' => '/admin/users/create',
        'controller' => 'UserController',
        'action' => 'create',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'POST',
        'path' => '/admin/users/store',
        'controller' => 'UserController',
        'action' => 'store',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'GET',
        'path' => '/admin/users/edit/{id}',
        'controller' => 'UserController',
        'action' => 'edit',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'POST',
        'path' => '/admin/users/update/{id}',
        'controller' => 'UserController',
        'action' => 'update',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'POST',
        'path' => '/admin/users/delete/{id}',
        'controller' => 'UserController',
        'action' => 'delete',
        'middleware' => [AuthMiddleware::class]
    ],
    
    // ----------------------------------------
    // 角色管理
    // ----------------------------------------
    [
        'method' => 'GET',
        'path' => '/admin/roles',
        'controller' => 'RoleController',
        'action' => 'index',
        'middleware' => [AuthMiddleware::class, AdminMiddleware::class]
    ],
    [
        'method' => 'GET',
        'path' => '/admin/roles/create',
        'controller' => 'RoleController',
        'action' => 'create',
        'middleware' => [AuthMiddleware::class, AdminMiddleware::class]
    ],
    [
        'method' => 'POST',
        'path' => '/admin/roles/store',
        'controller' => 'RoleController',
        'action' => 'store',
        'middleware' => [AuthMiddleware::class, AdminMiddleware::class]
    ],
    [
        'method' => 'GET',
        'path' => '/admin/roles/edit/{id}',
        'controller' => 'RoleController',
        'action' => 'edit',
        'middleware' => [AuthMiddleware::class, AdminMiddleware::class]
    ],
    [
        'method' => 'POST',
        'path' => '/admin/roles/update/{id}',
        'controller' => 'RoleController',
        'action' => 'update',
        'middleware' => [AuthMiddleware::class, AdminMiddleware::class]
    ],
    [
        'method' => 'POST',
        'path' => '/admin/roles/delete/{id}',
        'controller' => 'RoleController',
        'action' => 'delete',
        'middleware' => [AuthMiddleware::class, AdminMiddleware::class]
    ],
    
    // ----------------------------------------
    // 功能管理
    // ----------------------------------------
    [
        'method' => 'GET',
        'path' => '/admin/functions',
        'controller' => 'FunctionController',
        'action' => 'index',
        'middleware' => [AuthMiddleware::class, AdminMiddleware::class]
    ],
    [
        'method' => 'GET',
        'path' => '/admin/functions/create',
        'controller' => 'FunctionController',
        'action' => 'create',
        'middleware' => [AuthMiddleware::class, AdminMiddleware::class]
    ],
    [
        'method' => 'POST',
        'path' => '/admin/functions/store',
        'controller' => 'FunctionController',
        'action' => 'store',
        'middleware' => [AuthMiddleware::class, AdminMiddleware::class]
    ],
    [
        'method' => 'GET',
        'path' => '/admin/functions/edit/{id}',
        'controller' => 'FunctionController',
        'action' => 'edit',
        'middleware' => [AuthMiddleware::class, AdminMiddleware::class]
    ],
    [
        'method' => 'POST',
        'path' => '/admin/functions/update/{id}',
        'controller' => 'FunctionController',
        'action' => 'update',
        'middleware' => [AuthMiddleware::class, AdminMiddleware::class]
    ],
    [
        'method' => 'POST',
        'path' => '/admin/functions/delete/{id}',
        'controller' => 'FunctionController',
        'action' => 'delete',
        'middleware' => [AuthMiddleware::class, AdminMiddleware::class]
    ],
    
    // ----------------------------------------
    // 操作紀錄
    // ----------------------------------------
    [
        'method' => 'GET',
        'path' => '/admin/action-logs',
        'controller' => 'ActionLogController',
        'action' => 'index',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'GET',
        'path' => '/admin/action-logs/export',
        'controller' => 'ActionLogController',
        'action' => 'export',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'GET',
        'path' => '/admin/action-logs/{id}',
        'controller' => 'ActionLogController',
        'action' => 'show',
        'middleware' => [AuthMiddleware::class]
    ],
    
    // ----------------------------------------
    // 登入紀錄
    // ----------------------------------------
    [
        'method' => 'GET',
        'path' => '/admin/login-logs',
        'controller' => 'LoginLogController',
        'action' => 'index',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'GET',
        'path' => '/admin/login-logs/statistics',
        'controller' => 'LoginLogController',
        'action' => 'statistics',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'GET',
        'path' => '/admin/login-logs/export',
        'controller' => 'LoginLogController',
        'action' => 'export',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'GET',
        'path' => '/admin/login-logs/chart-data',
        'controller' => 'LoginLogController',
        'action' => 'chartData',
        'middleware' => [AuthMiddleware::class]
    ],
    
    // ----------------------------------------
    // 產品維護
    // ----------------------------------------
    [
        'method' => 'GET',
        'path' => '/admin/products',
        'controller' => 'ProductController',
        'action' => 'index',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'GET',
        'path' => '/admin/products/edit/{id}',
        'controller' => 'ProductController',
        'action' => 'edit',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'POST',
        'path' => '/admin/products/update/{id}',
        'controller' => 'ProductController',
        'action' => 'update',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'POST',
        'path' => '/admin/products/upload-image/{id}',
        'controller' => 'ProductController',
        'action' => 'uploadImage',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'POST',
        'path' => '/admin/products/delete-image/{id}/{imageId}',
        'controller' => 'ProductController',
        'action' => 'deleteImage',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'POST',
        'path' => '/admin/products/set-main-image/{id}/{imageId}',
        'controller' => 'ProductController',
        'action' => 'setMainImage',
        'middleware' => [AuthMiddleware::class]
    ],
    // 產品明細
    [
        'method' => 'POST',
        'path' => '/admin/products/{id}/details',
        'controller' => 'ProductController',
        'action' => 'storeDetail',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'POST',
        'path' => '/admin/products/{id}/details/{detailId}',
        'controller' => 'ProductController',
        'action' => 'updateDetail',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'POST',
        'path' => '/admin/products/{id}/details/{detailId}/delete',
        'controller' => 'ProductController',
        'action' => 'deleteDetail',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'POST',
        'path' => '/admin/products/{id}/details/{detailId}/upload-image',
        'controller' => 'ProductController',
        'action' => 'uploadDetailImage',
        'middleware' => [AuthMiddleware::class]
    ],
    // 產品 FAQ
    [
        'method' => 'POST',
        'path' => '/admin/products/{id}/faqs',
        'controller' => 'ProductController',
        'action' => 'storeFaq',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'POST',
        'path' => '/admin/products/{id}/faqs/{faqId}',
        'controller' => 'ProductController',
        'action' => 'updateFaq',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'POST',
        'path' => '/admin/products/{id}/faqs/{faqId}/delete',
        'controller' => 'ProductController',
        'action' => 'deleteFaq',
        'middleware' => [AuthMiddleware::class]
    ],
    // 技術手冊
    [
        'method' => 'POST',
        'path' => '/admin/products/{id}/manuals',
        'controller' => 'ProductController',
        'action' => 'uploadManualFile',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'POST',
        'path' => '/admin/products/{id}/manuals/{manualId}',
        'controller' => 'ProductController',
        'action' => 'updateManual',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'POST',
        'path' => '/admin/products/{id}/manuals/{manualId}/delete',
        'controller' => 'ProductController',
        'action' => 'deleteManualFile',
        'middleware' => [AuthMiddleware::class]
    ],
    
    // ----------------------------------------
    // 圖片輪播維護
    // ----------------------------------------
    [
        'method' => 'GET',
        'path' => '/admin/banners',
        'controller' => 'BannerController',
        'action' => 'index',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'GET',
        'path' => '/admin/banners/create',
        'controller' => 'BannerController',
        'action' => 'create',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'POST',
        'path' => '/admin/banners/store',
        'controller' => 'BannerController',
        'action' => 'store',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'GET',
        'path' => '/admin/banners/edit/{id}',
        'controller' => 'BannerController',
        'action' => 'edit',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'POST',
        'path' => '/admin/banners/update/{id}',
        'controller' => 'BannerController',
        'action' => 'update',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'POST',
        'path' => '/admin/banners/delete/{id}',
        'controller' => 'BannerController',
        'action' => 'delete',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'POST',
        'path' => '/admin/banners/toggle-status/{id}',
        'controller' => 'BannerController',
        'action' => 'toggleStatus',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'GET',
        'path' => '/admin/banners/sort',
        'controller' => 'BannerController',
        'action' => 'sort',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'POST',
        'path' => '/admin/banners/update-sort',
        'controller' => 'BannerController',
        'action' => 'updateSort',
        'middleware' => [AuthMiddleware::class]
    ],
    
    // ----------------------------------------
    // 知識分享維護
    // ----------------------------------------
    [
        'method' => 'GET',
        'path' => '/admin/knowledge',
        'controller' => 'KnowledgeController',
        'action' => 'index',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'GET',
        'path' => '/admin/knowledge/create',
        'controller' => 'KnowledgeController',
        'action' => 'create',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'POST',
        'path' => '/admin/knowledge/store',
        'controller' => 'KnowledgeController',
        'action' => 'store',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'GET',
        'path' => '/admin/knowledge/edit/{id}',
        'controller' => 'KnowledgeController',
        'action' => 'edit',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'POST',
        'path' => '/admin/knowledge/update/{id}',
        'controller' => 'KnowledgeController',
        'action' => 'update',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'POST',
        'path' => '/admin/knowledge/delete/{id}',
        'controller' => 'KnowledgeController',
        'action' => 'delete',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'POST',
        'path' => '/admin/knowledge/toggle-status/{id}',
        'controller' => 'KnowledgeController',
        'action' => 'toggleStatus',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'POST',
        'path' => '/admin/knowledge/toggle-pinned/{id}',
        'controller' => 'KnowledgeController',
        'action' => 'togglePinned',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'POST',
        'path' => '/admin/knowledge/upload-editor-image',
        'controller' => 'KnowledgeController',
        'action' => 'uploadEditorImage',
        'middleware' => [AuthMiddleware::class]
    ],
    
    // ----------------------------------------
    // Cibes 品牌維護
    // ----------------------------------------
    [
        'method' => 'GET',
        'path' => '/admin/cibes',
        'controller' => 'CibesController',
        'action' => 'index',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'GET',
        'path' => '/admin/cibes/create',
        'controller' => 'CibesController',
        'action' => 'create',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'POST',
        'path' => '/admin/cibes/store',
        'controller' => 'CibesController',
        'action' => 'store',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'GET',
        'path' => '/admin/cibes/edit/{id}',
        'controller' => 'CibesController',
        'action' => 'edit',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'POST',
        'path' => '/admin/cibes/update/{id}',
        'controller' => 'CibesController',
        'action' => 'update',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'POST',
        'path' => '/admin/cibes/delete/{id}',
        'controller' => 'CibesController',
        'action' => 'delete',
        'middleware' => [AuthMiddleware::class]
    ],
    [
        'method' => 'POST',
        'path' => '/admin/cibes/toggle-status/{id}',
        'controller' => 'CibesController',
        'action' => 'toggleStatus',
        'middleware' => [AuthMiddleware::class]
    ],
];
