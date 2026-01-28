-- ========================================
-- 圖片輪播資料表
-- ========================================

-- 輪播圖片表
CREATE TABLE IF NOT EXISTS Banners (
    id INT PRIMARY KEY AUTO_INCREMENT,
    position ENUM('hero', 'features') NOT NULL DEFAULT 'hero' COMMENT '輪播位置: hero=首頁主輪播, features=下方三圖輪播',
    media_type ENUM('image', 'video') NOT NULL DEFAULT 'image' COMMENT '媒體類型: image=圖片, video=影片',
    title VARCHAR(200) NOT NULL COMMENT '標題',
    description TEXT COMMENT '描述',
    image_path VARCHAR(500) COMMENT '圖片路徑',
    video_path VARCHAR(500) COMMENT '影片路徑',
    link_url VARCHAR(500) COMMENT '連結網址',
    link_target ENUM('_self', '_blank') DEFAULT '_self' COMMENT '連結開啟方式',
    sort_order INT DEFAULT 0 COMMENT '排序（數字小的排前面）',
    status TINYINT(1) DEFAULT 1 COMMENT '狀態 (1=顯示, 0=停用)',
    start_date DATE COMMENT '開始顯示日期',
    end_date DATE COMMENT '結束顯示日期',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_position (position),
    INDEX idx_status (status),
    INDEX idx_sort_order (sort_order),
    INDEX idx_dates (start_date, end_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='輪播圖片表';

-- ========================================
-- 修改現有資料表（如果資料表已存在）
-- ========================================
-- ALTER TABLE Banners ADD COLUMN position ENUM('hero', 'features') NOT NULL DEFAULT 'hero' COMMENT '輪播位置' AFTER id;
-- ALTER TABLE Banners ADD COLUMN media_type ENUM('image', 'video') NOT NULL DEFAULT 'image' COMMENT '媒體類型' AFTER position;
-- ALTER TABLE Banners ADD COLUMN video_path VARCHAR(500) COMMENT '影片路徑' AFTER image_path;
-- ALTER TABLE Banners MODIFY COLUMN image_path VARCHAR(500) COMMENT '圖片路徑';
-- ALTER TABLE Banners ADD INDEX idx_position (position);

-- 新增功能到選單
INSERT INTO AcFunctions (function_name, function_code, parent_id, url, icon, sort_order, is_menu, status) VALUES
('圖片輪播維護', 'banners', (SELECT id FROM (SELECT id FROM AcFunctions WHERE function_code = 'content_management' LIMIT 1) AS temp), '/admin/banners', 'fa-images', 2, 1, 1);

-- 如果沒有內容管理父選單，先建立它
INSERT IGNORE INTO AcFunctions (function_name, function_code, parent_id, url, icon, sort_order, is_menu, status) VALUES
('內容管理', 'content_management', NULL, NULL, 'fa-folder', 10, 1, 1);
