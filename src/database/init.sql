-- =============================================
-- 鴻展盟管理系統 - 資料庫建立腳本
-- =============================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 建立資料庫
CREATE DATABASE IF NOT EXISTS `gdaliftc_maindata` 
DEFAULT CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `gdaliftc_maindata`;

-- =============================================
-- 1. AcUsers (使用者帳號)
-- =============================================
DROP TABLE IF EXISTS `AcUsers`;
CREATE TABLE `AcUsers` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '使用者ID',
    `username` VARCHAR(50) NOT NULL UNIQUE COMMENT '帳號',
    `email` VARCHAR(100) NOT NULL UNIQUE COMMENT '電子郵件',
    `password` VARCHAR(255) NOT NULL COMMENT '密碼 (bcrypt)',
    `display_name` VARCHAR(100) NULL COMMENT '顯示名稱',
    `phone` VARCHAR(20) NULL COMMENT '電話',
    `avatar` VARCHAR(255) NULL COMMENT '頭像路徑',
    `status` TINYINT NOT NULL DEFAULT 1 COMMENT '狀態: 0=停用, 1=啟用',
    `email_verified_at` DATETIME NULL COMMENT '郵件驗證時間',
    `password_changed_at` DATETIME NULL COMMENT '密碼最後變更時間',
    `remember_token` VARCHAR(100) NULL COMMENT '記住我 Token',
    `reset_token` VARCHAR(100) NULL COMMENT '重設密碼 Token',
    `reset_token_expires_at` DATETIME NULL COMMENT 'Token 過期時間',
    `last_login_at` DATETIME NULL COMMENT '最後登入時間',
    `last_login_ip` VARCHAR(45) NULL COMMENT '最後登入 IP',
    `login_attempts` TINYINT NOT NULL DEFAULT 0 COMMENT '登入失敗次數',
    `locked_until` DATETIME NULL COMMENT '鎖定至時間',
    `created_by` INT UNSIGNED NULL COMMENT '建立者ID',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '建立時間',
    `updated_at` DATETIME NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新時間',
    
    INDEX `idx_username` (`username`),
    INDEX `idx_email` (`email`),
    INDEX `idx_status` (`status`),
    INDEX `idx_created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='使用者帳號';

-- =============================================
-- 2. AcRoles (權限角色)
-- =============================================
DROP TABLE IF EXISTS `AcRoles`;
CREATE TABLE `AcRoles` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '角色ID',
    `role_code` VARCHAR(50) NOT NULL UNIQUE COMMENT '角色代碼',
    `role_name` VARCHAR(100) NOT NULL COMMENT '角色名稱',
    `level` TINYINT NOT NULL DEFAULT 3 COMMENT '權限層級: 1=admin, 2=host, 3=user',
    `description` TEXT NULL COMMENT '角色描述',
    `can_assign_to` TINYINT NULL COMMENT '可指派給哪個層級',
    `status` TINYINT NOT NULL DEFAULT 1 COMMENT '狀態: 0=停用, 1=啟用',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '建立時間',
    `updated_at` DATETIME NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新時間',
    
    INDEX `idx_role_code` (`role_code`),
    INDEX `idx_level` (`level`),
    INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='權限角色';

-- =============================================
-- 3. AcFunctions (功能畫面)
-- =============================================
DROP TABLE IF EXISTS `AcFunctions`;
CREATE TABLE `AcFunctions` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '功能ID',
    `function_code` VARCHAR(50) NOT NULL UNIQUE COMMENT '功能代碼',
    `function_name` VARCHAR(100) NOT NULL COMMENT '功能名稱',
    `parent_id` INT UNSIGNED NULL COMMENT '父功能ID',
    `url` VARCHAR(255) NULL COMMENT '功能網址',
    `controller` VARCHAR(100) NULL COMMENT '對應控制器',
    `action` VARCHAR(100) NULL COMMENT '對應方法',
    `icon` VARCHAR(50) NULL COMMENT '圖示 class',
    `sort_order` INT NOT NULL DEFAULT 0 COMMENT '排序',
    `is_menu` TINYINT NOT NULL DEFAULT 1 COMMENT '是否顯示在選單: 0=否, 1=是',
    `min_level` TINYINT NOT NULL DEFAULT 3 COMMENT '最低權限層級',
    `status` TINYINT NOT NULL DEFAULT 1 COMMENT '狀態: 0=停用, 1=啟用',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '建立時間',
    `updated_at` DATETIME NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新時間',
    
    INDEX `idx_function_code` (`function_code`),
    INDEX `idx_parent_id` (`parent_id`),
    INDEX `idx_sort_order` (`sort_order`),
    INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='功能畫面';

-- =============================================
-- 4. AcUserRoles (使用者-角色關聯)
-- =============================================
DROP TABLE IF EXISTS `AcUserRoles`;
CREATE TABLE `AcUserRoles` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT 'ID',
    `user_id` INT UNSIGNED NOT NULL COMMENT '使用者ID',
    `role_id` INT UNSIGNED NOT NULL COMMENT '角色ID',
    `assigned_by` INT UNSIGNED NULL COMMENT '指派者ID',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '建立時間',
    
    UNIQUE KEY `uk_user_role` (`user_id`, `role_id`),
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_role_id` (`role_id`),
    
    FOREIGN KEY (`user_id`) REFERENCES `AcUsers`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`role_id`) REFERENCES `AcRoles`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`assigned_by`) REFERENCES `AcUsers`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='使用者角色關聯';

-- =============================================
-- 5. AcRoleFunctions (角色-功能關聯)
-- =============================================
DROP TABLE IF EXISTS `AcRoleFunctions`;
CREATE TABLE `AcRoleFunctions` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT 'ID',
    `role_id` INT UNSIGNED NOT NULL COMMENT '角色ID',
    `function_id` INT UNSIGNED NOT NULL COMMENT '功能ID',
    `can_view` TINYINT NOT NULL DEFAULT 1 COMMENT '可檢視',
    `can_create` TINYINT NOT NULL DEFAULT 0 COMMENT '可新增',
    `can_edit` TINYINT NOT NULL DEFAULT 0 COMMENT '可編輯',
    `can_delete` TINYINT NOT NULL DEFAULT 0 COMMENT '可刪除',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '建立時間',
    
    UNIQUE KEY `uk_role_function` (`role_id`, `function_id`),
    INDEX `idx_role_id` (`role_id`),
    INDEX `idx_function_id` (`function_id`),
    
    FOREIGN KEY (`role_id`) REFERENCES `AcRoles`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`function_id`) REFERENCES `AcFunctions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='角色功能關聯';

-- =============================================
-- 6. ActionLogs (操作紀錄)
-- =============================================
DROP TABLE IF EXISTS `ActionLogs`;
CREATE TABLE `ActionLogs` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '紀錄ID',
    `user_id` INT UNSIGNED NULL COMMENT '使用者ID',
    `username` VARCHAR(50) NULL COMMENT '帳號 (冗餘欄位)',
    `action` VARCHAR(50) NOT NULL COMMENT '操作類型: view, create, update, delete',
    `controller` VARCHAR(100) NOT NULL COMMENT '控制器名稱',
    `method` VARCHAR(100) NOT NULL COMMENT '方法名稱',
    `url` VARCHAR(500) NOT NULL COMMENT '請求網址',
    `http_method` VARCHAR(10) NOT NULL COMMENT 'HTTP 方法',
    `ip_address` VARCHAR(45) NOT NULL COMMENT 'IP 位址',
    `user_agent` VARCHAR(500) NULL COMMENT '瀏覽器資訊',
    `request_data` JSON NULL COMMENT '請求資料 (JSON)',
    `response_code` INT NULL COMMENT '回應狀態碼',
    `description` TEXT NULL COMMENT '操作描述',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '建立時間',
    
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_action` (`action`),
    INDEX `idx_controller` (`controller`),
    INDEX `idx_created_at` (`created_at`),
    INDEX `idx_ip_address` (`ip_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='操作紀錄';

-- =============================================
-- 7. LoginLogs (登入紀錄)
-- =============================================
DROP TABLE IF EXISTS `LoginLogs`;
CREATE TABLE `LoginLogs` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '紀錄ID',
    `user_id` INT UNSIGNED NULL COMMENT '使用者ID',
    `username` VARCHAR(50) NULL COMMENT '嘗試登入的帳號',
    `ip_address` VARCHAR(45) NOT NULL COMMENT 'IP 位址',
    `user_agent` VARCHAR(500) NULL COMMENT '瀏覽器資訊',
    `login_status` TINYINT NOT NULL COMMENT '登入狀態: 0=失敗, 1=成功',
    `failure_reason` VARCHAR(100) NULL COMMENT '失敗原因',
    `login_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '登入時間',
    `logout_at` DATETIME NULL COMMENT '登出時間',
    `session_id` VARCHAR(100) NULL COMMENT 'Session ID',
    
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_login_status` (`login_status`),
    INDEX `idx_login_at` (`login_at`),
    INDEX `idx_ip_address` (`ip_address`),
    
    FOREIGN KEY (`user_id`) REFERENCES `AcUsers`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='登入紀錄';

-- =============================================
-- 種子資料 (Seed Data)
-- =============================================

-- 預設角色
INSERT INTO `AcRoles` (`role_code`, `role_name`, `level`, `description`, `can_assign_to`, `status`) VALUES
('admin', '最大管理者', 1, '開發者使用，擁有所有系統權限', 2, 1),
('host', '使用者管理者', 2, '客戶最大管理權限，可管理使用者', 3, 1),
('user', '使用者', 3, '客戶一般管理者', NULL, 1);

-- 預設管理員帳號 (密碼: Admin@123)
INSERT INTO `AcUsers` (`username`, `email`, `password`, `display_name`, `status`, `email_verified_at`) VALUES
('admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '系統管理員', 1, NOW());

-- 指派 admin 角色
INSERT INTO `AcUserRoles` (`user_id`, `role_id`, `assigned_by`) VALUES
(1, 1, NULL);

-- 預設功能選單
INSERT INTO `AcFunctions` (`function_code`, `function_name`, `parent_id`, `url`, `controller`, `action`, `icon`, `sort_order`, `is_menu`, `min_level`) VALUES
-- 主選單
('dashboard', '控制台', NULL, '/admin/dashboard', 'DashboardController', 'index', 'fa-tachometer-alt', 1, 1, 3),

-- 內容管理 (id=2)
('content', '內容管理', NULL, NULL, NULL, NULL, 'fa-folder-open', 10, 1, 2),

-- 內容管理子選單 (parent_id=2)
('products', '產品維護', 2, '/admin/products', 'ProductController', 'index', 'fa-box', 11, 1, 2),
('banners', '圖片輪播維護', 2, '/admin/banners', 'BannerController', 'index', 'fa-images', 12, 1, 2),
('knowledge', '知識分享維護', 2, '/admin/knowledge', 'KnowledgeController', 'index', 'fa-lightbulb', 13, 1, 2),

-- 系統管理 (id=6)
('system', '系統管理', NULL, NULL, NULL, NULL, 'fa-cog', 100, 1, 1),

-- 系統管理子選單 (parent_id=6)
('users', '帳號管理', 6, '/admin/users', 'UserController', 'index', 'fa-users', 101, 1, 2),
('roles', '權限管理', 6, '/admin/roles', 'RoleController', 'index', 'fa-user-shield', 102, 1, 1),
('functions', '功能管理', 6, '/admin/functions', 'FunctionController', 'index', 'fa-sitemap', 103, 1, 1),

-- 系統紀錄 (id=10)
('logs', '系統紀錄', NULL, NULL, NULL, NULL, 'fa-history', 200, 1, 2),

-- 系統紀錄子選單 (parent_id=10)
('action-logs', '操作紀錄', 10, '/admin/action-logs', 'ActionLogController', 'index', 'fa-clipboard-list', 201, 1, 2),
('login-logs', '登入紀錄', 10, '/admin/login-logs', 'LoginLogController', 'index', 'fa-sign-in-alt', 202, 1, 2);

-- 為 admin 角色指派所有功能權限
INSERT INTO `AcRoleFunctions` (`role_id`, `function_id`, `can_view`, `can_create`, `can_edit`, `can_delete`)
SELECT 1, id, 1, 1, 1, 1 FROM `AcFunctions`;

SET FOREIGN_KEY_CHECKS = 1;

-- =============================================
-- 完成！
-- 預設帳號: admin
-- 預設密碼: Admin@123
-- =============================================
