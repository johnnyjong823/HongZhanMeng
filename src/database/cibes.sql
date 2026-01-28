-- Cibes 品牌資料表
CREATE TABLE IF NOT EXISTS `Cibes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL COMMENT '品牌名稱',
    `category` VARCHAR(50) NULL COMMENT '類別代碼（對應 CodeDef）',
    `content` TEXT NULL COMMENT '內容描述',
    `image_path` VARCHAR(255) NULL COMMENT '圖片路徑',
    `sort_order` INT DEFAULT 0 COMMENT '排序',
    `status` TINYINT(1) DEFAULT 1 COMMENT '狀態：1=顯示, 0=停用',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '建立時間',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新時間',
    INDEX `idx_category` (`category`),
    INDEX `idx_status` (`status`),
    INDEX `idx_sort_order` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Cibes品牌資料表';

-- Cibes 類別定義（加入 CodeDef）
INSERT INTO `CodeDef` (`code_type`, `code_id`, `code_name`, `code_value`, `sort_order`, `status`, `remark`) VALUES
('cibes_category', 'home_lift', '家用電梯', '', 1, 1, 'Cibes 家用電梯類別'),
('cibes_category', 'platform_lift', '升降平台', '', 2, 1, 'Cibes 升降平台類別'),
('cibes_category', 'commercial_lift', '商用電梯', '', 3, 1, 'Cibes 商用電梯類別'),
('cibes_category', 'outdoor_lift', '戶外電梯', '', 4, 1, 'Cibes 戶外電梯類別'),
('cibes_category', 'accessories', '配件', '', 5, 1, 'Cibes 配件類別');
