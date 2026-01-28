-- =============================================
-- 產品維護 - 資料庫建立腳本
-- =============================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

USE `hongzhanmeng`;

-- =============================================
-- 1. Products (產品資料)
-- =============================================
DROP TABLE IF EXISTS `Products`;
CREATE TABLE `Products` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '產品ID',
    `product_code` VARCHAR(50) NULL COMMENT '產品編號',
    `product_name` VARCHAR(200) NOT NULL COMMENT '產品名稱',
    `category_id` INT UNSIGNED NULL COMMENT '類別ID',
    `category_name` VARCHAR(100) NULL COMMENT '類別名稱 (冗餘)',
    `size` VARCHAR(100) NULL COMMENT '尺寸',
    `model` VARCHAR(100) NULL COMMENT '規格型號',
    `description` TEXT NULL COMMENT '詳細介紹',
    `installation` TEXT NULL COMMENT '安裝說明',
    `faq` TEXT NULL COMMENT '常見問題',
    `manual_file` VARCHAR(255) NULL COMMENT '手冊檔案路徑',
    `manual_filename` VARCHAR(255) NULL COMMENT '手冊原始檔名',
    `status` TINYINT NOT NULL DEFAULT 1 COMMENT '狀態: 0=停用, 1=顯示',
    `sort_order` INT NOT NULL DEFAULT 0 COMMENT '排序',
    `created_by` INT UNSIGNED NULL COMMENT '建立者ID',
    `updated_by` INT UNSIGNED NULL COMMENT '更新者ID',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '建立時間',
    `updated_at` DATETIME NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新時間',
    
    INDEX `idx_product_code` (`product_code`),
    INDEX `idx_category_id` (`category_id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_sort_order` (`sort_order`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='產品資料';

-- =============================================
-- 2. ProductImages (產品圖片)
-- =============================================
DROP TABLE IF EXISTS `ProductImages`;
CREATE TABLE `ProductImages` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '圖片ID',
    `product_id` INT UNSIGNED NOT NULL COMMENT '產品ID',
    `image_path` VARCHAR(255) NOT NULL COMMENT '圖片路徑',
    `image_filename` VARCHAR(255) NULL COMMENT '原始檔名',
    `image_type` VARCHAR(20) DEFAULT 'gallery' COMMENT '圖片類型: main=主圖, gallery=相簿',
    `sort_order` INT NOT NULL DEFAULT 0 COMMENT '排序',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '建立時間',
    
    INDEX `idx_product_id` (`product_id`),
    INDEX `idx_image_type` (`image_type`),
    INDEX `idx_sort_order` (`sort_order`),
    
    FOREIGN KEY (`product_id`) REFERENCES `Products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='產品圖片';

-- =============================================
-- 3. ProductCategories (產品類別)
-- =============================================
DROP TABLE IF EXISTS `ProductCategories`;
CREATE TABLE `ProductCategories` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '類別ID',
    `category_name` VARCHAR(100) NOT NULL COMMENT '類別名稱',
    `parent_id` INT UNSIGNED NULL COMMENT '父類別ID',
    `description` TEXT NULL COMMENT '類別描述',
    `sort_order` INT NOT NULL DEFAULT 0 COMMENT '排序',
    `status` TINYINT NOT NULL DEFAULT 1 COMMENT '狀態: 0=停用, 1=啟用',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '建立時間',
    `updated_at` DATETIME NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新時間',
    
    INDEX `idx_parent_id` (`parent_id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_sort_order` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='產品類別';

-- =============================================
-- 新增功能選單
-- =============================================

-- 先檢查是否有「內容管理」父選單，沒有就建立
INSERT INTO `AcFunctions` (`function_code`, `function_name`, `parent_id`, `url`, `controller`, `action`, `icon`, `sort_order`, `is_menu`, `min_level`) VALUES
('content', '內容管理', NULL, NULL, NULL, NULL, 'fa-folder-open', 50, 1, 2);

-- 取得內容管理的 ID
SET @content_id = LAST_INSERT_ID();

-- 新增產品維護功能
INSERT INTO `AcFunctions` (`function_code`, `function_name`, `parent_id`, `url`, `controller`, `action`, `icon`, `sort_order`, `is_menu`, `min_level`) VALUES
('products', '產品維護', @content_id, '/admin/products', 'ProductController', 'index', 'fa-box', 51, 1, 2);

-- 取得產品維護的 function_id
SET @product_func_id = LAST_INSERT_ID();

-- 為 admin 角色指派產品維護權限
INSERT INTO `AcRoleFunctions` (`role_id`, `function_id`, `can_view`, `can_create`, `can_edit`, `can_delete`)
SELECT 1, @product_func_id, 1, 1, 1, 1;

-- 為 host 角色也指派權限
INSERT INTO `AcRoleFunctions` (`role_id`, `function_id`, `can_view`, `can_create`, `can_edit`, `can_delete`)
SELECT 2, @product_func_id, 1, 1, 1, 1;

-- 預設產品類別
INSERT INTO `ProductCategories` (`category_name`, `sort_order`, `status`) VALUES
('一般產品', 1, 1),
('特殊產品', 2, 1);

SET FOREIGN_KEY_CHECKS = 1;

-- =============================================
-- 完成！
-- =============================================
