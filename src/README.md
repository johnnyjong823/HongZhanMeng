# 鴻展盟管理系統

## 專案結構

```
src/
├── app/                    # 應用程式目錄
│   ├── Controllers/        # 控制器
│   ├── Models/             # 模型
│   ├── Views/              # 視圖模板
│   ├── Middleware/         # 中介層
│   ├── Filters/            # 過濾器
│   ├── Services/           # 服務層
│   └── Helpers/            # 輔助函式
├── config/                 # 設定檔
│   ├── app.php             # 應用程式設定
│   ├── database.php        # 資料庫設定
│   ├── mail.php            # 郵件設定
│   └── routes.php          # 路由設定
├── core/                   # 核心框架
│   ├── App.php             # 應用程式主類別
│   ├── Router.php          # 路由器
│   ├── Database.php        # 資料庫連線
│   ├── Controller.php      # 基礎控制器
│   ├── Model.php           # 基礎模型
│   └── View.php            # 視圖引擎
├── public/                 # 公開目錄 (網站根目錄)
│   ├── index.php           # 入口檔案
│   ├── .htaccess           # Apache 設定
│   └── assets/             # 靜態資源
├── storage/                # 儲存目錄
│   └── logs/               # 日誌檔案
└── start-server.bat        # Windows 開發伺服器啟動腳本
```

## 系統需求

- PHP 8.0+
- MySQL 5.7+ / MariaDB 10.3+
- Apache (含 mod_rewrite) 或 Nginx

## 安裝步驟

### 1. 資料庫設定

1. 建立 MySQL 資料庫 `hongzhanmeng`
2. 匯入資料庫結構 (參考 docs/database.sql)
3. 修改 `config/database.php` 設定

```php
return [
    'host' => 'localhost',
    'port' => 3306,
    'database' => 'hongzhanmeng',
    'username' => 'your_username',
    'password' => 'your_password',
    'charset' => 'utf8mb4',
];
```

### 2. 開發環境啟動

#### Windows
雙擊執行 `start-server.bat`

#### 命令列
```bash
cd src/public
php -S localhost:8801 router.php
```

### 3. 訪問系統

- 前台：http://localhost:8801
- 後台：http://localhost:8801/admin
- 登入：http://localhost:8801/account/login

### 預設帳號
- 帳號：admin
- 密碼：Admin@123

## cPanel 部署

1. 將 `src` 目錄內容上傳到 `public_html` 或其子目錄
2. 確保 `storage` 目錄可寫入 (chmod 755)
3. 修改 `config/database.php` 為正式環境設定
4. 修改 `config/app.php` 中的 `debug` 為 `false`
5. 修改 `config/app.php` 中的 `url` 為正式網址

## 權限等級

| 等級 | 名稱 | 說明 |
|------|------|------|
| 1 | Admin | 系統管理員，完整權限 |
| 2 | Host | 營運管理員，可管理一般使用者 |
| 3 | User | 一般使用者 |

## 主要功能

- ✅ 使用者登入/登出
- ✅ 忘記密碼/重設密碼
- ✅ 使用者管理 (CRUD)
- ✅ 角色管理 (RBAC)
- ✅ 功能權限管理
- ✅ 操作紀錄 (RecordActionFilter)
- ✅ 登入紀錄
- ✅ Session 60 分鐘逾時

## 技術架構

- 自製輕量 MVC 框架
- PDO 資料庫連線
- bcrypt 密碼加密
- CSRF Token 防護
- Session 管理

## 授權

MIT License
