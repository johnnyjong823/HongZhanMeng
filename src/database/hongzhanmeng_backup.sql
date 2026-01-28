-- MySQL dump 10.13  Distrib 8.4.6, for Win64 (x86_64)
--
-- Host: localhost    Database: hongzhanmeng
-- ------------------------------------------------------
-- Server version	8.4.6

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `acfunctions`
--

DROP TABLE IF EXISTS `acfunctions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `acfunctions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '功能ID',
  `function_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '功能代碼',
  `function_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '功能名稱',
  `parent_id` int unsigned DEFAULT NULL COMMENT '父功能ID',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '功能網址',
  `controller` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '對應控制器',
  `action` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '對應方法',
  `icon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '圖示 class',
  `sort_order` int NOT NULL DEFAULT '0' COMMENT '排序',
  `is_menu` tinyint NOT NULL DEFAULT '1' COMMENT '是否顯示在選單: 0=否, 1=是',
  `min_level` tinyint NOT NULL DEFAULT '3' COMMENT '最低權限層級',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '狀態: 0=停用, 1=啟用',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '建立時間',
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新時間',
  PRIMARY KEY (`id`),
  UNIQUE KEY `function_code` (`function_code`),
  KEY `idx_function_code` (`function_code`),
  KEY `idx_parent_id` (`parent_id`),
  KEY `idx_sort_order` (`sort_order`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='功能畫面';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acfunctions`
--

LOCK TABLES `acfunctions` WRITE;
/*!40000 ALTER TABLE `acfunctions` DISABLE KEYS */;
INSERT INTO `acfunctions` VALUES (1,'dashboard','控制台',NULL,'/admin/dashboard','DashboardController','index','fa-dashboard',1,1,3,1,'2026-01-19 17:40:52',NULL),(2,'system','系統管理',NULL,NULL,NULL,NULL,'fa-cog',100,1,1,1,'2026-01-19 17:40:52',NULL),(3,'users','帳號管理',2,'/admin/users','UserController','index','fa-users',101,1,2,1,'2026-01-19 17:40:52',NULL),(4,'roles','權限管理',2,'/admin/roles','RoleController','index','fa-shield',102,1,1,1,'2026-01-19 17:40:52',NULL),(5,'functions','功能管理',2,'/admin/functions','FunctionController','index','fa-list',103,1,1,1,'2026-01-19 17:40:52',NULL),(6,'action-logs','操作紀錄',16,'/admin/action-logs','ActionLogController','index','fa-history',201,1,2,1,'2026-01-19 17:40:52','2026-01-27 20:27:26'),(7,'login-logs','登入紀錄',16,'/admin/login-logs','LoginLogController','index','fa-sign-in',202,1,2,1,'2026-01-19 17:40:52','2026-01-27 20:27:29'),(8,'content','內容管理',NULL,NULL,NULL,NULL,'fa-folder-open',50,1,2,1,'2026-01-19 21:08:11',NULL),(9,'products','產品維護',8,'/admin/products','ProductController','index','fa-box',51,1,2,1,'2026-01-19 21:08:11',NULL),(16,'logs','系統紀錄',NULL,NULL,NULL,NULL,'fa-history',200,1,2,1,'2026-01-27 20:27:11',NULL),(17,'banners','圖片輪播維護',8,'/admin/banners','BannerController','index','fa-images',12,1,2,1,'2026-01-27 20:28:04','2026-01-27 20:29:00'),(18,'knowledge','知識分享維護',8,'/admin/knowledge','KnowledgeController','index','fa-lightbulb',13,1,2,1,'2026-01-27 20:28:04','2026-01-27 20:29:00');
/*!40000 ALTER TABLE `acfunctions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acrolefunctions`
--

DROP TABLE IF EXISTS `acrolefunctions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `acrolefunctions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `role_id` int unsigned NOT NULL COMMENT '角色ID',
  `function_id` int unsigned NOT NULL COMMENT '功能ID',
  `can_view` tinyint NOT NULL DEFAULT '1' COMMENT '可檢視',
  `can_create` tinyint NOT NULL DEFAULT '0' COMMENT '可新增',
  `can_edit` tinyint NOT NULL DEFAULT '0' COMMENT '可編輯',
  `can_delete` tinyint NOT NULL DEFAULT '0' COMMENT '可刪除',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '建立時間',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_role_function` (`role_id`,`function_id`),
  KEY `idx_role_id` (`role_id`),
  KEY `idx_function_id` (`function_id`),
  CONSTRAINT `acrolefunctions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `acroles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `acrolefunctions_ibfk_2` FOREIGN KEY (`function_id`) REFERENCES `acfunctions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='角色功能關聯';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acrolefunctions`
--

LOCK TABLES `acrolefunctions` WRITE;
/*!40000 ALTER TABLE `acrolefunctions` DISABLE KEYS */;
INSERT INTO `acrolefunctions` VALUES (1,1,1,1,1,1,1,'2026-01-19 17:40:52'),(2,1,2,1,1,1,1,'2026-01-19 17:40:52'),(3,1,3,1,1,1,1,'2026-01-19 17:40:52'),(4,1,4,1,1,1,1,'2026-01-19 17:40:52'),(5,1,5,1,1,1,1,'2026-01-19 17:40:52'),(6,1,6,1,1,1,1,'2026-01-19 17:40:52'),(7,1,7,1,1,1,1,'2026-01-19 17:40:52'),(8,1,9,1,1,1,1,'2026-01-19 21:08:11'),(9,2,9,1,1,1,1,'2026-01-19 21:08:11'),(10,1,8,1,1,1,1,'2026-01-27 20:27:31'),(11,1,16,1,1,1,1,'2026-01-27 20:27:31');
/*!40000 ALTER TABLE `acrolefunctions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acroles`
--

DROP TABLE IF EXISTS `acroles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `acroles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `role_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '角色代碼',
  `role_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '角色名稱',
  `level` tinyint NOT NULL DEFAULT '3' COMMENT '權限層級: 1=admin, 2=host, 3=user',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT '角色描述',
  `can_assign_to` tinyint DEFAULT NULL COMMENT '可指派給哪個層級',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '狀態: 0=停用, 1=啟用',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '建立時間',
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新時間',
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_code` (`role_code`),
  KEY `idx_role_code` (`role_code`),
  KEY `idx_level` (`level`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='權限角色';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acroles`
--

LOCK TABLES `acroles` WRITE;
/*!40000 ALTER TABLE `acroles` DISABLE KEYS */;
INSERT INTO `acroles` VALUES (1,'admin','最大管理者',1,'開發者使用，擁有所有系統權限',2,1,'2026-01-19 17:40:52',NULL),(2,'host','使用者管理者',2,'客戶最大管理權限，可管理使用者',3,1,'2026-01-19 17:40:52',NULL),(3,'user','使用者',3,'客戶一般管理者',NULL,1,'2026-01-19 17:40:52',NULL);
/*!40000 ALTER TABLE `acroles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `actionlogs`
--

DROP TABLE IF EXISTS `actionlogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `actionlogs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '紀錄ID',
  `user_id` int unsigned DEFAULT NULL COMMENT '使用者ID',
  `username` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '帳號 (冗餘欄位)',
  `action` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '操作類型: view, create, update, delete',
  `controller` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '控制器名稱',
  `method` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '方法名稱',
  `url` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '請求網址',
  `http_method` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'HTTP 方法',
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'IP 位址',
  `user_agent` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '瀏覽器資訊',
  `request_data` json DEFAULT NULL COMMENT '請求資料 (JSON)',
  `response_code` int DEFAULT NULL COMMENT '回應狀態碼',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT '操作描述',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '建立時間',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_action` (`action`),
  KEY `idx_controller` (`controller`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_ip_address` (`ip_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='操作紀錄';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `actionlogs`
--

LOCK TABLES `actionlogs` WRITE;
/*!40000 ALTER TABLE `actionlogs` DISABLE KEYS */;
/*!40000 ALTER TABLE `actionlogs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acuserroles`
--

DROP TABLE IF EXISTS `acuserroles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `acuserroles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int unsigned NOT NULL COMMENT '使用者ID',
  `role_id` int unsigned NOT NULL COMMENT '角色ID',
  `assigned_by` int unsigned DEFAULT NULL COMMENT '指派者ID',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '建立時間',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_user_role` (`user_id`,`role_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_role_id` (`role_id`),
  KEY `assigned_by` (`assigned_by`),
  CONSTRAINT `acuserroles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `acusers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `acuserroles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `acroles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `acuserroles_ibfk_3` FOREIGN KEY (`assigned_by`) REFERENCES `acusers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='使用者角色關聯';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acuserroles`
--

LOCK TABLES `acuserroles` WRITE;
/*!40000 ALTER TABLE `acuserroles` DISABLE KEYS */;
INSERT INTO `acuserroles` VALUES (1,1,1,NULL,'2026-01-19 17:40:52');
/*!40000 ALTER TABLE `acuserroles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acusers`
--

DROP TABLE IF EXISTS `acusers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `acusers` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '使用者ID',
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '帳號',
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '電子郵件',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '密碼 (bcrypt)',
  `display_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '顯示名稱',
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '電話',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '頭像路徑',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '狀態: 0=停用, 1=啟用',
  `email_verified_at` datetime DEFAULT NULL COMMENT '郵件驗證時間',
  `password_changed_at` datetime DEFAULT NULL COMMENT '密碼最後變更時間',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '記住我 Token',
  `reset_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '重設密碼 Token',
  `reset_token_expires_at` datetime DEFAULT NULL COMMENT 'Token 過期時間',
  `last_login_at` datetime DEFAULT NULL COMMENT '最後登入時間',
  `last_login_ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '最後登入 IP',
  `login_attempts` tinyint NOT NULL DEFAULT '0' COMMENT '登入失敗次數',
  `locked_until` datetime DEFAULT NULL COMMENT '鎖定至時間',
  `created_by` int unsigned DEFAULT NULL COMMENT '建立者ID',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '建立時間',
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新時間',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_username` (`username`),
  KEY `idx_email` (`email`),
  KEY `idx_status` (`status`),
  KEY `idx_created_by` (`created_by`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='使用者帳號';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acusers`
--

LOCK TABLES `acusers` WRITE;
/*!40000 ALTER TABLE `acusers` DISABLE KEYS */;
INSERT INTO `acusers` VALUES (1,'admin','admin@example.com','$2y$10$Xm/qxnrUJLHKMt10EWzoGOe2allIfFHfHnOpIUy.g8Ncp/dbEhPmC','系統管理員',NULL,NULL,1,'2026-01-19 17:40:52',NULL,NULL,NULL,NULL,'2026-01-28 17:02:32','::1',0,NULL,NULL,'2026-01-19 17:40:52','2026-01-28 17:02:32');
/*!40000 ALTER TABLE `acusers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `banners` (
  `id` int NOT NULL AUTO_INCREMENT,
  `position` enum('hero','features') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'hero',
  `media_type` enum('image','video') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'image',
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '標題',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT '描述',
  `image_path` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_path` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '連結網址',
  `link_target` enum('_self','_blank') COLLATE utf8mb4_unicode_ci DEFAULT '_self' COMMENT '連結開啟方式',
  `sort_order` int DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) DEFAULT '1' COMMENT '狀態',
  `start_date` date DEFAULT NULL COMMENT '開始顯示日期',
  `end_date` date DEFAULT NULL COMMENT '結束顯示日期',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_sort_order` (`sort_order`),
  KEY `idx_dates` (`start_date`,`end_date`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='輪播圖片表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banners`
--

LOCK TABLES `banners` WRITE;
/*!40000 ALTER TABLE `banners` DISABLE KEYS */;
INSERT INTO `banners` VALUES (1,'hero','video','首頁影片','',NULL,'/uploads/banners/videos/video_20260127212751_6978bd57c0957.mp4','','_self',1,1,NULL,NULL,'2026-01-27 13:28:13','2026-01-27 13:28:13'),(2,'hero','image','首頁圖片','','/uploads/banners/banner_20260128135738_6979a5523130e.webp',NULL,'','_self',2,1,NULL,NULL,'2026-01-28 05:57:38','2026-01-28 05:57:38'),(3,'features','image','節省空間、易於安裝配備','無底坑、無機房設計，最小的佔地\r\n即可安裝，改造住宅結構需求低。','/uploads/banners/banner_20260128140439_6979a6f7e877f.webp',NULL,'','_self',1,1,NULL,NULL,'2026-01-28 06:04:41','2026-01-28 06:19:42'),(4,'features','image','高質感設計、無障礙體驗','鋼化雙層夾膠玻璃車廂、柔和的照明與簡易操作，兼顧美觀與使用友善，提升生活便利與住宅價值。','/uploads/banners/banner_20260128140610_6979a7523d105.webp',NULL,'','_self',2,1,NULL,NULL,'2026-01-28 06:06:10','2026-01-28 06:09:31'),(5,'features','image','轎底安全觸板','當電梯下降時，如果有任何東西進入電梯下面，一旦觸及安全板，電梯將自動停止，確保使用上的絕對安全','/uploads/banners/banner_20260128140645_6979a775bf30e.webp',NULL,'','_self',3,1,NULL,NULL,'2026-01-28 06:06:46','2026-01-28 06:19:32');
/*!40000 ALTER TABLE `banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cibes`
--

DROP TABLE IF EXISTS `cibes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cibes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '品牌名稱',
  `category` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '類別代碼（對應 CodeDef）',
  `content` text COLLATE utf8mb4_unicode_ci COMMENT '內容描述',
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '圖片路徑',
  `sort_order` int DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) DEFAULT '1' COMMENT '狀態：1=顯示, 0=停用',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '建立時間',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新時間',
  PRIMARY KEY (`id`),
  KEY `idx_category` (`category`),
  KEY `idx_status` (`status`),
  KEY `idx_sort_order` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Cibes品牌資料表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cibes`
--

LOCK TABLES `cibes` WRITE;
/*!40000 ALTER TABLE `cibes` DISABLE KEYS */;
/*!40000 ALTER TABLE `cibes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codedef`
--

DROP TABLE IF EXISTS `codedef`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `codedef` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `code_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '參數類型',
  `code_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '參數代碼',
  `code_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '參數名稱',
  `code_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '參數值',
  `sort_order` int NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '狀態: 0=停用, 1=啟用',
  `remark` text COLLATE utf8mb4_unicode_ci COMMENT '備註',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '建立時間',
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新時間',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_type_code` (`code_type`,`code_id`),
  KEY `idx_code_type` (`code_type`),
  KEY `idx_status` (`status`),
  KEY `idx_sort_order` (`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系統參數定義';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codedef`
--

LOCK TABLES `codedef` WRITE;
/*!40000 ALTER TABLE `codedef` DISABLE KEYS */;
INSERT INTO `codedef` VALUES (1,'product_category','general','一般產品',NULL,1,1,'一般產品類別','2026-01-19 21:12:53',NULL),(2,'product_category','special','特殊產品',NULL,2,1,'特殊產品類別','2026-01-19 21:12:53',NULL),(3,'cibes_category','home_lift','家用電梯','',1,1,'Cibes 家用電梯類別','2026-01-26 19:31:50',NULL),(4,'cibes_category','platform_lift','升降平台','',2,1,'Cibes 升降平台類別','2026-01-26 19:31:50',NULL),(5,'cibes_category','commercial_lift','商用電梯','',3,1,'Cibes 商用電梯類別','2026-01-26 19:31:50',NULL),(6,'cibes_category','outdoor_lift','戶外電梯','',4,1,'Cibes 戶外電梯類別','2026-01-26 19:31:50',NULL),(7,'cibes_category','accessories','配件','',5,1,'Cibes 配件類別','2026-01-26 19:31:50',NULL),(8,'knowledge_category','purchase_guide','選購指南',NULL,1,1,NULL,'2026-01-27 11:20:07',NULL),(9,'knowledge_category','space_planning','空間規劃',NULL,2,1,NULL,'2026-01-27 11:20:07',NULL),(10,'knowledge_category','brand_selection','品牌選擇',NULL,3,1,NULL,'2026-01-27 11:20:07',NULL),(11,'knowledge_category','patent_tech','專利技術',NULL,4,1,NULL,'2026-01-27 11:20:07',NULL),(12,'knowledge_category','other','其他',NULL,99,1,NULL,'2026-01-27 11:20:07',NULL);
/*!40000 ALTER TABLE `codedef` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `knowledge`
--

DROP TABLE IF EXISTS `knowledge`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `knowledge` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '知識標題',
  `category` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '知識類別',
  `content` text COLLATE utf8mb4_unicode_ci COMMENT '知識內容',
  `image_path` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '封面圖片路徑',
  `is_pinned` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否置頂 (0:否, 1:是)',
  `sort_order` int NOT NULL DEFAULT '0' COMMENT '排序 (數字越小排越前)',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '狀態 (0:停用, 1:顯示)',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '建立時間',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新時間',
  PRIMARY KEY (`id`),
  KEY `idx_category` (`category`),
  KEY `idx_status` (`status`),
  KEY `idx_is_pinned` (`is_pinned`),
  KEY `idx_sort_order` (`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='知識分享資料表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `knowledge`
--

LOCK TABLES `knowledge` WRITE;
/*!40000 ALTER TABLE `knowledge` DISABLE KEYS */;
INSERT INTO `knowledge` VALUES (1,'EcoSilent 螺桿驅動系統','patent_tech','Ascenda 獨特的 EcoSilent 靜音螺桿驅動系統，採用創新螺桿技術，將運行聲音降至約等同於圖書館的 40 分貝寧靜，並同步節省約 45% 用電量。','/assets/images/frontend/節省空間.jpg',1,1,1,'2026-01-27 11:20:09','2026-01-27 11:20:09'),(2,'9 個住宅電梯的好處','purchase_guide','到了 2025 年，越來越多台灣家庭開始重新思考「居家便利性」與「未來生活的彈性」。住宅電梯不僅提供無障礙的垂直移動，更能提升房屋價值與生活品質。','/assets/images/frontend/高質感設計.jpg',1,2,1,'2026-01-27 11:20:09','2026-01-27 11:20:09'),(3,'避免常見的家用電梯安裝問題','space_planning','你是否正在考慮在家中安裝一部家用電梯？這是一次能徹底改變生活的升級。本文將協助您避免常見的安裝錯誤，確保順利完成安裝。','/assets/images/frontend/轎底安全觸板.jpg',1,3,1,'2026-01-27 11:20:09','2026-01-27 11:20:09');
/*!40000 ALTER TABLE `knowledge` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loginlogs`
--

DROP TABLE IF EXISTS `loginlogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loginlogs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '紀錄ID',
  `user_id` int unsigned DEFAULT NULL COMMENT '使用者ID',
  `username` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '嘗試登入的帳號',
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'IP 位址',
  `user_agent` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '瀏覽器資訊',
  `login_status` tinyint NOT NULL COMMENT '登入狀態: 0=失敗, 1=成功',
  `failure_reason` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '失敗原因',
  `login_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '登入時間',
  `logout_at` datetime DEFAULT NULL COMMENT '登出時間',
  `session_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Session ID',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_login_status` (`login_status`),
  KEY `idx_login_at` (`login_at`),
  KEY `idx_ip_address` (`ip_address`),
  CONSTRAINT `loginlogs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `acusers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='登入紀錄';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loginlogs`
--

LOCK TABLES `loginlogs` WRITE;
/*!40000 ALTER TABLE `loginlogs` DISABLE KEYS */;
INSERT INTO `loginlogs` VALUES (1,NULL,'admin','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36',0,'密碼錯誤','2026-01-19 17:47:02',NULL,NULL),(2,1,'admin','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36',1,NULL,'2026-01-19 17:49:05',NULL,'0ede07ebc1849680c6c5fdf80396cf67'),(3,1,'admin','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36',1,NULL,'2026-01-19 17:55:10',NULL,'o0nnfhtedsgb3ip819uint4auh'),(4,1,'admin','::1','Mozilla/5.0 (Windows NT 10.0; Microsoft Windows 10.0.26200; zh-TW) PowerShell/7.5.4',1,NULL,'2026-01-19 20:15:05',NULL,'20csl46u1hsvu36ulrl9bajrp4'),(5,1,'admin','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36',1,NULL,'2026-01-19 20:15:49',NULL,'ae5g1kphlacq7e6qrfa6h8tg28'),(6,1,'admin','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36',1,NULL,'2026-01-20 10:02:02',NULL,'dku50p31tma586bkqafkrs92ci'),(7,1,'admin','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36',1,NULL,'2026-01-26 19:35:16',NULL,'nd1uchjemj7n1ld82phl4pti28'),(8,1,'admin','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36',1,NULL,'2026-01-27 20:19:49',NULL,'71p8oucsk3mj97t3k5qk8arhsd'),(9,1,'admin','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36',1,NULL,'2026-01-27 21:27:23',NULL,'655e8ea485d8d1c327243049d8655d90'),(10,1,'admin','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1,NULL,'2026-01-28 13:52:17',NULL,'vva3apsupretu82oth57bft9sb'),(11,1,'admin','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',1,NULL,'2026-01-28 17:02:32',NULL,'bgmsi57443433hfrolrbfr1gbf');
/*!40000 ALTER TABLE `loginlogs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productcategories`
--

DROP TABLE IF EXISTS `productcategories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `productcategories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '類別ID',
  `category_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '類別名稱',
  `parent_id` int unsigned DEFAULT NULL COMMENT '父類別ID',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT '類別描述',
  `sort_order` int NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '狀態: 0=停用, 1=啟用',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '建立時間',
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新時間',
  PRIMARY KEY (`id`),
  KEY `idx_parent_id` (`parent_id`),
  KEY `idx_status` (`status`),
  KEY `idx_sort_order` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='產品類別';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productcategories`
--

LOCK TABLES `productcategories` WRITE;
/*!40000 ALTER TABLE `productcategories` DISABLE KEYS */;
/*!40000 ALTER TABLE `productcategories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productdetails`
--

DROP TABLE IF EXISTS `productdetails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `productdetails` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '明細ID',
  `product_id` int unsigned NOT NULL COMMENT '產品ID',
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '標題',
  `content` text COLLATE utf8mb4_unicode_ci COMMENT '內容',
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '圖片路徑',
  `image_filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '原始檔名',
  `sort_order` int NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '狀態: 0=停用, 1=顯示',
  `created_by` int unsigned DEFAULT NULL COMMENT '建立者ID',
  `updated_by` int unsigned DEFAULT NULL COMMENT '更新者ID',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '建立時間',
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新時間',
  PRIMARY KEY (`id`),
  KEY `idx_product_id` (`product_id`),
  KEY `idx_sort_order` (`sort_order`),
  KEY `idx_status` (`status`),
  CONSTRAINT `productdetails_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='產品明細';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productdetails`
--

LOCK TABLES `productdetails` WRITE;
/*!40000 ALTER TABLE `productdetails` DISABLE KEYS */;
/*!40000 ALTER TABLE `productdetails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productfaqs`
--

DROP TABLE IF EXISTS `productfaqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `productfaqs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'FAQ ID',
  `product_id` int unsigned NOT NULL COMMENT '產品ID',
  `question` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '問題',
  `answer` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '回答',
  `sort_order` int NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '狀態: 0=停用, 1=顯示',
  `created_by` int unsigned DEFAULT NULL COMMENT '建立者ID',
  `updated_by` int unsigned DEFAULT NULL COMMENT '更新者ID',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '建立時間',
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新時間',
  PRIMARY KEY (`id`),
  KEY `idx_product_id` (`product_id`),
  KEY `idx_sort_order` (`sort_order`),
  KEY `idx_status` (`status`),
  CONSTRAINT `productfaqs_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='產品Q&A';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productfaqs`
--

LOCK TABLES `productfaqs` WRITE;
/*!40000 ALTER TABLE `productfaqs` DISABLE KEYS */;
INSERT INTO `productfaqs` VALUES (1,2,'我家已經裝潢好了，還可以安裝 Ascenda 家用電梯嗎？需要預留底坑或機房嗎？','可以，這正是的核心價值「兼容已裝潢住宅設計」，Ascenda\n採用螺桿驅動技術，實現「無底坑、無機房」的安裝技術。\n我們無需向下開挖或增建機房，僅需在樓板間開出一個合適的開口或利用樓梯間搭建軌道，最快約個工作天即可完成安裝，對居家生活近乎無干擾',1,1,1,1,'2026-01-28 17:03:56','2026-01-28 17:05:24'),(2,2,'Ascenda 電梯的「創新艙口蓋板系統」是什麼？安全嗎？','這是為了不浪費樓上空間的貼心設計，當電梯下行後，樓層開口會被一個堅固的「艙口蓋板」自動填平，您可以像平常一樣在上面行走通過，在安全性上，頂蓋配備高靈敏度的重量感測器。只要偵測到上方有人或重物，電梯系統會強制鎖定無法上升，直到障礙物移除，確保家人絕對安全。',2,1,1,NULL,'2026-01-28 17:05:17',NULL),(3,2,'如果電梯運行中突然停電，我會被關在裡面嗎？','不會。Ascenda全系列皆標配備「緊急備用電源系統」，若遇家中斷電，電池系統會立即接手，將電梯平穩送至最近的樓層並開啟艙門，讓您安全離開。同時，車廂內另設有手動下降系統(MLS)，確保在任何情況下都能安全下行至地面。',3,1,1,1,'2026-01-28 17:05:57','2026-01-28 17:07:16'),(4,2,'安裝一台Ascenda電梯，家裡至少需要預留多少空間？','僅約 10 平方公尺即可。Ascenda以極致節省空間而聞名。\n是小坪數透天或樓中樓的最佳選擇。\n若您有輪椅進出需求，我們亦有或 XL 型號可供選擇規劃。\n相較於傳統電梯，能省下約 30% ~ 50%的寶貴室內空間。',4,1,1,NULL,'2026-01-28 17:06:59',NULL),(5,2,'家中有長輩或輪椅使用者，Ascenda 的操作方便嗎？','非常直覺且省力。三種不同位置與需求的簡單控制介面，讓全家人無需學習都能輕鬆上手',5,1,1,1,'2026-01-28 17:07:36','2026-01-28 17:22:35'),(6,2,'電梯運行時的聲音大嗎？\n會不會影響家人休息？','Ascenda 採用先進的靜音驅動技術,運行聲音極低(約45-55分貝),相當於圖書館內的安靜程度或家中冰箱運作的聲音。\n即便安裝在客廳或臥室旁,也能維持居家的環境品質,讓移動過程簡單安靜。',6,1,1,NULL,'2026-01-28 17:08:33',NULL),(7,2,'螺桿式電梯的後續保養會很麻煩嗎？','相較於傳統電梯,Ascenda 的螺桿驅動系統結構更為精簡、零件更少,因此故障率極低且更加耐用。一般家庭使用頻率下,通常僅需每年進行1-2次的例行性檢查與上油保養即可。鴻展盟擁有專業的原廠認證技師團隊,能提供您最即時、安心的售後維護服務。',7,1,1,NULL,'2026-01-28 17:09:02',NULL),(8,2,'安裝  Ascenda 電梯會破壞原本的房屋結\n構或地板承重嗎？','完全不會。Ascenda 擁有獨特的「一體式井道」設計,也就是電梯自帶骨架支撐,不需要倚賴牆面受力,且其整體重量輕盈,一般的樓板承重皆可安全安裝,無須擔心因加裝電梯而造成房屋結構受損或產生裂縫。',8,1,1,NULL,'2026-01-28 17:09:31',NULL),(9,2,'電梯運行起來會很耗電嗎?\n需要申請工業用電嗎?','不用,Ascenda 採用節能環保的驅動技術,待機時的耗電量\n極低。在正常使用頻率下,其耗電量與一台家用滾筒洗衣機或冰箱相去不遠,且適用於一般家庭電壓(依機型配置),無須額外申請特殊的電力系統,長期使用下來既環保又經濟。',9,1,1,NULL,'2026-01-28 17:09:56',NULL),(10,2,'螺桿式電梯坐起來會有「失重感」或頭暈嗎?','螺桿驅動最大的優勢之一就是「極致的平穩感」。不同於传统鋼索電梯起步和停止時會有明顯的升降加速度(導致失重感或不適),Ascenda 運行的過程非常線性且平穩,就像坐在家中的沙發上一樣慢慢移動,非常適合對晃動敏感的長輩、孕婦或容易暈車的家人使用',10,1,1,NULL,'2026-01-28 17:10:23',NULL);
/*!40000 ALTER TABLE `productfaqs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productimages`
--

DROP TABLE IF EXISTS `productimages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `productimages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '圖片ID',
  `product_id` int unsigned NOT NULL COMMENT '產品ID',
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '圖片路徑',
  `image_filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '原始檔名',
  `image_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'gallery' COMMENT '圖片類型: main=主圖, gallery=相簿',
  `sort_order` int NOT NULL DEFAULT '0' COMMENT '排序',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '建立時間',
  PRIMARY KEY (`id`),
  KEY `idx_product_id` (`product_id`),
  KEY `idx_image_type` (`image_type`),
  KEY `idx_sort_order` (`sort_order`),
  CONSTRAINT `productimages_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='產品圖片';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productimages`
--

LOCK TABLES `productimages` WRITE;
/*!40000 ALTER TABLE `productimages` DISABLE KEYS */;
INSERT INTO `productimages` VALUES (1,1,'/uploads/products/1/696e2d2016cc0_1768828192.jpg','S__10747907.jpg','main',0,'2026-01-19 21:09:52');
/*!40000 ALTER TABLE `productimages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productmanuals`
--

DROP TABLE IF EXISTS `productmanuals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `productmanuals` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '手冊ID',
  `product_id` int unsigned NOT NULL COMMENT '產品ID',
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '手冊名稱',
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '檔案路徑',
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '原始檔名',
  `file_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '檔案類型(pdf/doc/xlsx)',
  `file_size` int unsigned DEFAULT NULL COMMENT '檔案大小(bytes)',
  `sort_order` int NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '狀態: 0=停用, 1=顯示',
  `created_by` int unsigned DEFAULT NULL COMMENT '建立者ID',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '建立時間',
  PRIMARY KEY (`id`),
  KEY `idx_product_id` (`product_id`),
  KEY `idx_sort_order` (`sort_order`),
  KEY `idx_status` (`status`),
  CONSTRAINT `productmanuals_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='產品技術手冊';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productmanuals`
--

LOCK TABLES `productmanuals` WRITE;
/*!40000 ALTER TABLE `productmanuals` DISABLE KEYS */;
INSERT INTO `productmanuals` VALUES (1,2,'技術手冊','/uploads/products/2/manuals/manual_1769591481_6979d2b9cb494.pdf','型錄.pdf','pdf',9072039,0,1,1,'2026-01-28 17:11:21');
/*!40000 ALTER TABLE `productmanuals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '產品ID',
  `product_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '產品編號',
  `slug` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '產品代碼(英文)',
  `product_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '產品名稱',
  `category_id` int unsigned DEFAULT NULL COMMENT '類別ID',
  `category_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '類別名稱 (冗餘)',
  `size` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '尺寸',
  `model` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '規格型號',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT '詳細介紹',
  `short_description` text COLLATE utf8mb4_unicode_ci COMMENT '簡短介紹',
  `installation` text COLLATE utf8mb4_unicode_ci COMMENT '安裝說明',
  `faq` text COLLATE utf8mb4_unicode_ci COMMENT '常見問題',
  `manual_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '手冊檔案路徑',
  `manual_filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '手冊原始檔名',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '狀態: 0=停用, 1=顯示',
  `is_system` tinyint NOT NULL DEFAULT '0' COMMENT '系統預設產品(不可刪除)',
  `sort_order` int NOT NULL DEFAULT '0' COMMENT '排序',
  `created_by` int unsigned DEFAULT NULL COMMENT '建立者ID',
  `updated_by` int unsigned DEFAULT NULL COMMENT '更新者ID',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '建立時間',
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新時間',
  PRIMARY KEY (`id`),
  KEY `idx_product_code` (`product_code`),
  KEY `idx_category_id` (`category_id`),
  KEY `idx_status` (`status`),
  KEY `idx_sort_order` (`sort_order`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_slug` (`slug`),
  KEY `idx_is_system` (`is_system`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='產品資料';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Test01',NULL,'Test',NULL,NULL,'111','111','111',NULL,'111','111',NULL,NULL,1,0,0,1,1,'2026-01-19 21:09:33','2026-01-19 21:10:28'),(2,'ASCENDA','ascenda','Ascenda愛生達',NULL,NULL,NULL,NULL,'愛生達（Ascenda）是專業的電梯設備品牌，致力於提供高品質的電梯解決方案。','專業電梯設備品牌',NULL,NULL,NULL,NULL,1,1,1,NULL,1,'2026-01-28 17:01:00','2026-01-28 17:11:59');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'hongzhanmeng'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-01-28 18:19:31
