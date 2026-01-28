-- =============================================
-- 知識分享資料表
-- =============================================

-- 建立知識分享資料表
CREATE TABLE IF NOT EXISTS `knowledge` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL COMMENT '知識標題',
    `category` VARCHAR(50) NOT NULL COMMENT '知識類別',
    `content` TEXT COMMENT '知識內容',
    `image_path` VARCHAR(500) COMMENT '封面圖片路徑',
    `is_pinned` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '是否置頂 (0:否, 1:是)',
    `sort_order` INT NOT NULL DEFAULT 0 COMMENT '排序 (數字越小排越前)',
    `status` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '狀態 (0:停用, 1:顯示)',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '建立時間',
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新時間',
    INDEX `idx_category` (`category`),
    INDEX `idx_status` (`status`),
    INDEX `idx_is_pinned` (`is_pinned`),
    INDEX `idx_sort_order` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='知識分享資料表';

-- 新增知識分享類別到 CodeDef 表
INSERT INTO `codedef` (`code_type`, `code_id`, `code_name`, `sort_order`, `status`) VALUES
('knowledge_category', 'purchase_guide', '選購指南', 1, 1),
('knowledge_category', 'space_planning', '空間規劃', 2, 1),
('knowledge_category', 'brand_selection', '品牌選擇', 3, 1),
('knowledge_category', 'patent_tech', '專利技術', 4, 1),
('knowledge_category', 'other', '其他', 99, 1)
ON DUPLICATE KEY UPDATE `code_name` = VALUES(`code_name`), `sort_order` = VALUES(`sort_order`);

-- 插入範例資料 (可選)
INSERT INTO `knowledge` (`title`, `category`, `content`, `image_path`, `is_pinned`, `sort_order`, `status`) VALUES
('EcoSilent 螺桿驅動系統', 'patent_tech', 'Ascenda 獨特的 EcoSilent 靜音螺桿驅動系統，採用創新螺桿技術，將運行聲音降至約等同於圖書館的 40 分貝寧靜，並同步節省約 45% 用電量。', '/assets/images/frontend/節省空間.jpg', 1, 1, 1),
('9 個住宅電梯的好處', 'purchase_guide', '到了 2025 年，越來越多台灣家庭開始重新思考「居家便利性」與「未來生活的彈性」。住宅電梯不僅提供無障礙的垂直移動，更能提升房屋價值與生活品質。', '/assets/images/frontend/高質感設計.jpg', 1, 2, 1),
('避免常見的家用電梯安裝問題', 'space_planning', '你是否正在考慮在家中安裝一部家用電梯？這是一次能徹底改變生活的升級。本文將協助您避免常見的安裝錯誤，確保順利完成安裝。', '/assets/images/frontend/轎底安全觸板.jpg', 1, 3, 1);
