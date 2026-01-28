-- =============================================
-- CodeDef (系統參數) - 資料庫建立腳本
-- =============================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

USE `hongzhanmeng`;

-- =============================================
-- CodeDef (系統參數)
-- =============================================
DROP TABLE IF EXISTS `CodeDef`;
CREATE TABLE `CodeDef` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '流水號',
    `code_type` VARCHAR(50) NOT NULL COMMENT '參數類型 (如: product_category, status 等)',
    `code_id` VARCHAR(50) NOT NULL COMMENT '參數代碼',
    `code_name` VARCHAR(100) NOT NULL COMMENT '參數名稱',
    `code_value` VARCHAR(255) NULL COMMENT '參數值 (可選)',
    `sort_order` INT NOT NULL DEFAULT 0 COMMENT '排序',
    `status` TINYINT NOT NULL DEFAULT 1 COMMENT '狀態: 0=停用, 1=啟用',
    `remark` TEXT NULL COMMENT '備註',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '建立時間',
    `updated_at` DATETIME NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新時間',
    
    UNIQUE KEY `uk_type_code` (`code_type`, `code_id`),
    INDEX `idx_code_type` (`code_type`),
    INDEX `idx_status` (`status`),
    INDEX `idx_sort_order` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系統參數定義';

-- =============================================
-- 預設資料 - 產品類別
-- =============================================
INSERT INTO `CodeDef` (`code_type`, `code_id`, `code_name`, `code_value`, `sort_order`, `status`, `remark`) VALUES
('product_category', 'general', '一般產品', NULL, 1, 1, '一般產品類別'),
('product_category', 'special', '特殊產品', NULL, 2, 1, '特殊產品類別');

-- =============================================
-- 範例 - 其他類型參數 (可自行新增)
-- =============================================
-- INSERT INTO `CodeDef` (`code_type`, `code_id`, `code_name`, `code_value`, `sort_order`, `status`, `remark`) VALUES
-- ('order_status', 'pending', '待處理', '1', 1, 1, NULL),
-- ('order_status', 'processing', '處理中', '2', 2, 1, NULL),
-- ('order_status', 'completed', '已完成', '3', 3, 1, NULL),
-- ('payment_method', 'cash', '現金', NULL, 1, 1, NULL),
-- ('payment_method', 'credit', '信用卡', NULL, 2, 1, NULL);

SET FOREIGN_KEY_CHECKS = 1;

-- =============================================
-- 完成！
-- 使用方式：
-- 1. code_type: 參數類型，用來分組（如 product_category）
-- 2. code_id: 該類型下的唯一代碼
-- 3. code_name: 顯示名稱
-- 4. code_value: 額外的值（可選）
-- 5. sort_order: 排序（數字越小越前面）
-- 6. status: 1=啟用, 0=停用
-- 7. remark: 備註說明
-- =============================================
