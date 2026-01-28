-- =============================================
-- 產品維護 V2 - 資料庫更新腳本
-- 固定產品架構：明細、Q&A、技術手冊
-- 相容 MySQL 5.7+
-- =============================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

USE `hongzhanmeng`;

-- =============================================
-- 1. 更新 Products 資料表（新增欄位）
-- =============================================

-- 新增 slug 欄位（若不存在）
SET @exist_slug := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = 'hongzhanmeng' AND TABLE_NAME = 'Products' AND COLUMN_NAME = 'slug');
SET @sql_slug := IF(@exist_slug = 0, 
    'ALTER TABLE `Products` ADD COLUMN `slug` VARCHAR(50) NULL COMMENT ''產品代碼(英文)'' AFTER `product_code`', 
    'SELECT 1');
PREPARE stmt FROM @sql_slug;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 新增 short_description 欄位（若不存在）
SET @exist_short := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = 'hongzhanmeng' AND TABLE_NAME = 'Products' AND COLUMN_NAME = 'short_description');
SET @sql_short := IF(@exist_short = 0, 
    'ALTER TABLE `Products` ADD COLUMN `short_description` TEXT NULL COMMENT ''簡短介紹'' AFTER `description`', 
    'SELECT 1');
PREPARE stmt FROM @sql_short;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 新增 is_system 欄位（若不存在）
SET @exist_system := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = 'hongzhanmeng' AND TABLE_NAME = 'Products' AND COLUMN_NAME = 'is_system');
SET @sql_system := IF(@exist_system = 0, 
    'ALTER TABLE `Products` ADD COLUMN `is_system` TINYINT NOT NULL DEFAULT 0 COMMENT ''系統預設產品(不可刪除)'' AFTER `status`', 
    'SELECT 1');
PREPARE stmt FROM @sql_system;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 新增索引（若不存在）
SET @exist_idx_slug := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS 
    WHERE TABLE_SCHEMA = 'hongzhanmeng' AND TABLE_NAME = 'Products' AND INDEX_NAME = 'idx_slug');
SET @sql_idx_slug := IF(@exist_idx_slug = 0, 
    'CREATE INDEX `idx_slug` ON `Products` (`slug`)', 
    'SELECT 1');
PREPARE stmt FROM @sql_idx_slug;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @exist_idx_system := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS 
    WHERE TABLE_SCHEMA = 'hongzhanmeng' AND TABLE_NAME = 'Products' AND INDEX_NAME = 'idx_is_system');
SET @sql_idx_system := IF(@exist_idx_system = 0, 
    'CREATE INDEX `idx_is_system` ON `Products` (`is_system`)', 
    'SELECT 1');
PREPARE stmt FROM @sql_idx_system;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- =============================================
-- 2. ProductDetails (產品明細)
-- =============================================
DROP TABLE IF EXISTS `ProductDetails`;
CREATE TABLE `ProductDetails` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '明細ID',
    `product_id` INT UNSIGNED NOT NULL COMMENT '產品ID',
    `title` VARCHAR(200) NOT NULL COMMENT '標題',
    `content` TEXT NULL COMMENT '內容',
    `image_path` VARCHAR(255) NULL COMMENT '圖片路徑',
    `image_filename` VARCHAR(255) NULL COMMENT '原始檔名',
    `sort_order` INT NOT NULL DEFAULT 0 COMMENT '排序',
    `status` TINYINT NOT NULL DEFAULT 1 COMMENT '狀態: 0=停用, 1=顯示',
    `created_by` INT UNSIGNED NULL COMMENT '建立者ID',
    `updated_by` INT UNSIGNED NULL COMMENT '更新者ID',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '建立時間',
    `updated_at` DATETIME NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新時間',
    
    INDEX `idx_product_id` (`product_id`),
    INDEX `idx_sort_order` (`sort_order`),
    INDEX `idx_status` (`status`),
    
    FOREIGN KEY (`product_id`) REFERENCES `Products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='產品明細';

-- =============================================
-- 3. ProductFaqs (產品Q&A)
-- =============================================
DROP TABLE IF EXISTS `ProductFaqs`;
CREATE TABLE `ProductFaqs` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT 'FAQ ID',
    `product_id` INT UNSIGNED NOT NULL COMMENT '產品ID',
    `question` VARCHAR(500) NOT NULL COMMENT '問題',
    `answer` TEXT NOT NULL COMMENT '回答',
    `sort_order` INT NOT NULL DEFAULT 0 COMMENT '排序',
    `status` TINYINT NOT NULL DEFAULT 1 COMMENT '狀態: 0=停用, 1=顯示',
    `created_by` INT UNSIGNED NULL COMMENT '建立者ID',
    `updated_by` INT UNSIGNED NULL COMMENT '更新者ID',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '建立時間',
    `updated_at` DATETIME NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新時間',
    
    INDEX `idx_product_id` (`product_id`),
    INDEX `idx_sort_order` (`sort_order`),
    INDEX `idx_status` (`status`),
    
    FOREIGN KEY (`product_id`) REFERENCES `Products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='產品Q&A';

-- =============================================
-- 4. ProductManuals (技術手冊)
-- =============================================
DROP TABLE IF EXISTS `ProductManuals`;
CREATE TABLE `ProductManuals` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '手冊ID',
    `product_id` INT UNSIGNED NOT NULL COMMENT '產品ID',
    `title` VARCHAR(200) NOT NULL COMMENT '手冊名稱',
    `file_path` VARCHAR(255) NOT NULL COMMENT '檔案路徑',
    `filename` VARCHAR(255) NOT NULL COMMENT '原始檔名',
    `file_type` VARCHAR(20) NULL COMMENT '檔案類型(pdf/doc/xlsx)',
    `file_size` INT UNSIGNED NULL COMMENT '檔案大小(bytes)',
    `sort_order` INT NOT NULL DEFAULT 0 COMMENT '排序',
    `status` TINYINT NOT NULL DEFAULT 1 COMMENT '狀態: 0=停用, 1=顯示',
    `created_by` INT UNSIGNED NULL COMMENT '建立者ID',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '建立時間',
    
    INDEX `idx_product_id` (`product_id`),
    INDEX `idx_sort_order` (`sort_order`),
    INDEX `idx_status` (`status`),
    
    FOREIGN KEY (`product_id`) REFERENCES `Products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='產品技術手冊';

-- =============================================
-- 5. 插入預設產品資料 (愛生達)
-- =============================================

-- 先檢查是否已存在，不存在則插入
INSERT INTO `Products` (`product_code`, `slug`, `product_name`, `short_description`, `description`, `status`, `is_system`, `sort_order`, `created_at`)
SELECT 'ASCENDA', 'ascenda', 'Ascenda愛生達', '專業醫療設備品牌', '愛生達（Ascenda）是專業的醫療設備品牌，致力於提供高品質的醫療解決方案。', 1, 1, 1, NOW()
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM `Products` WHERE `slug` = 'ascenda'
);

SET FOREIGN_KEY_CHECKS = 1;

-- =============================================
-- 完成！
-- =============================================
