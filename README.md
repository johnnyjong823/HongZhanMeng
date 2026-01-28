# 鴻展盟管理系統

鴻展盟（HongZhanMeng）是一套基於 PHP MVC 架構開發的企業網站管理系統，包含前台展示與後台管理功能。

## 📋 專案資訊

| 項目 | 說明 |
|------|------|
| 開發語言 | PHP 8.x |
| 資料庫 | MySQL 5.7+ / MariaDB 10.x+ |
| 架構模式 | MVC (Model-View-Controller) |
| 前端框架 | Tailwind CSS |
| 部署環境 | cPanel + FTP |

## 🚀 功能特色

### 前台功能
- 首頁輪播 Banner 管理
- 產品展示頁面
- 知識文章系統
- 公司介紹頁面
- 聯絡我們表單

### 後台功能
- 帳號管理系統
- 角色權限管理
- 功能畫面管理
- 輪播圖管理
- 產品管理
- 知識文章管理
- 操作紀錄系統
- 登入紀錄系統

## 📁 專案結構

```
HongZhanMeng/
├── docs/               # 專案文件
├── src/                # 原始碼
│   ├── app/            # 應用程式
│   │   ├── Controllers/    # 控制器
│   │   ├── Models/         # 模型
│   │   ├── Views/          # 視圖
│   │   ├── Services/       # 服務層
│   │   ├── Middleware/     # 中介層
│   │   └── Helpers/        # 輔助函式
│   ├── config/         # 設定檔
│   ├── core/           # 核心框架
│   ├── database/       # 資料庫腳本
│   ├── deploy/         # 部署設定
│   ├── public/         # 公開資源 (網站根目錄)
│   └── storage/        # 儲存目錄 (日誌等)
└── README.md
```

## ⚙️ 環境需求

### PHP 需求
- PHP 8.0 以上 (建議 8.2+)
- 必要擴充套件：
  - pdo_mysql
  - mbstring
  - openssl
  - json
  - session
  - fileinfo
  - gd 或 imagick (選用)

### 資料庫需求
- MySQL 5.7+ 或 MariaDB 10.x+

## 🛠️ 安裝步驟

### 1. 下載專案
```bash
git clone https://github.com/johnnyjong823/HongZhanMeng.git
cd HongZhanMeng
```

### 2. 設定資料庫
1. 建立資料庫
2. 匯入 `src/database/init.sql` 初始化資料庫結構
3. 複製並修改設定檔：
```bash
cp src/config/database.php.example src/config/database.php
```

### 3. 設定應用程式
修改 `src/config/app.php` 中的基本設定：
```php
return [
    'name' => '鴻展盟',
    'url' => 'http://your-domain.com',
    'debug' => false,
    // ...
];
```

### 4. 設定檔案權限
```bash
chmod -R 755 src/storage/
chmod -R 755 src/public/uploads/
```

### 5. 啟動開發伺服器
```bash
cd src
php -S localhost:8000 -t public router.php
```

或使用 Windows 批次檔：
```bash
cd src
start-server.bat
```

## 🔐 權限層級

| 層級 | 角色代碼 | 說明 |
|------|----------|------|
| 1 | admin | 最大管理者（開發者使用） |
| 2 | host | 使用者管理者（客戶最大權限） |
| 3 | user | 一般使用者 |

## 📖 文件

詳細文件請參閱 `docs/` 目錄：

- [專案概述](docs/01-專案概述.md)
- [資料夾結構](docs/02-資料夾結構.md)
- [資料庫設計](docs/03-資料庫設計.md)
- [MVC 架構說明](docs/04-MVC架構說明.md)
- [權限系統設計](docs/05-權限系統設計.md)
- [操作紀錄系統](docs/06-操作紀錄系統.md)
- [登入驗證系統](docs/07-登入驗證系統.md)
- [密碼管理功能](docs/08-密碼管理功能.md)
- [部署指南](docs/09-部署指南.md)
- [API 路由設計](docs/10-API路由設計.md)

## 📝 授權

本專案為私有專案，未經授權請勿使用。

## 👤 開發團隊

必加廣告
