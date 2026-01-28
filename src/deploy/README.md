# cPanel 部署指南 - Final 資料夾測試版

本文件說明如何將專案上傳到 cPanel 的 `Final` 子資料夾進行測試，測試完成後再移至正式目錄。

---

## 📁 資料夾結構說明

```
deploy/
├── Final/                      # 測試階段用的 .htaccess
│   ├── .htaccess              # 根目錄規則 (處理 /Final/ 路徑)
│   └── public/
│       └── .htaccess          # public 目錄規則
├── production/                 # 正式上線用的 .htaccess
│   ├── .htaccess              # 根目錄規則
│   └── public/
│       └── .htaccess          # public 目錄規則
└── config/
    ├── app.cpanel.php         # 應用程式設定範本
    └── database.cpanel.php    # 資料庫設定範本
```

---

## 🚀 第一步：測試階段部署 (Final 資料夾)

### 1. 準備設定檔

```bash
# 複製設定檔
cp deploy/config/app.cpanel.php config/app.php
cp deploy/config/database.cpanel.php config/database.php
```

### 2. 修改 config/app.php

```php
'url' => 'https://your-domain.com/Final/public',
'debug' => true,  // 測試階段可設為 true
```

### 3. 修改 config/database.php

填入 cPanel 的資料庫資訊：
```php
'database' => 'cpanel帳號_hongzhanmeng',
'username' => 'cpanel帳號_dbuser',
'password' => '你的密碼',
```

### 4. 上傳檔案到 cPanel

使用 FTP 或 cPanel File Manager 上傳：

```
public_html/
└── Final/                          # 建立此資料夾
    ├── .htaccess                   # 使用 deploy/Final/.htaccess
    ├── app/
    ├── core/
    ├── config/
    ├── database/
    ├── storage/
    └── public/
        ├── .htaccess               # 使用 deploy/Final/public/.htaccess
        ├── index.php
        └── assets/
```

### 5. 設定目錄權限

```
Final/storage/          → 755
Final/storage/logs/     → 755
Final/public/uploads/   → 755
```

### 6. 匯入資料庫

1. 登入 cPanel > phpMyAdmin
2. 選擇已建立的資料庫
3. 匯入 `database/` 資料夾中的 SQL 檔案

### 7. 測試網址

```
https://your-domain.com/Final/public/
```

---

## ✅ 第二步：測試完成後移至正式目錄

### 1. 備份現有資料 (如有需要)

在 cPanel 中備份 `public_html` 原有的檔案

### 2. 移動檔案

將 `Final` 資料夾內的所有檔案移動到 `public_html` 根目錄：

```
public_html/
├── .htaccess                   # 使用 deploy/production/.htaccess
├── app/
├── core/
├── config/
├── database/
├── storage/
└── public/
    ├── .htaccess               # 使用 deploy/production/public/.htaccess
    ├── index.php
    └── assets/
```

### 3. 更新設定檔

**config/app.php**:
```php
'url' => 'https://your-domain.com',
'debug' => false,  // 正式環境必須關閉
```

### 4. 測試正式網址

```
https://your-domain.com/
```

### 5. 刪除 Final 資料夾

確認一切正常後，刪除 `Final` 資料夾

---

## 📋 快速檢查清單

### 測試階段
- [ ] 建立 cPanel 資料庫和使用者
- [ ] 修改 config/database.php
- [ ] 修改 config/app.php (url 指向 /Final/public)
- [ ] 上傳檔案到 Final 資料夾
- [ ] 複製 deploy/Final/.htaccess 到 Final/
- [ ] 複製 deploy/Final/public/.htaccess 到 Final/public/
- [ ] 設定目錄權限
- [ ] 匯入資料庫
- [ ] 測試網站功能

### 正式上線
- [ ] 移動檔案到 public_html 根目錄
- [ ] 複製 deploy/production/.htaccess 到根目錄
- [ ] 複製 deploy/production/public/.htaccess 到 public/
- [ ] 修改 config/app.php (url 移除 /Final/public)
- [ ] 設定 debug => false
- [ ] 測試所有功能
- [ ] 刪除 Final 資料夾

---

## ⚠️ 常見問題

### 404 錯誤
- 確認 .htaccess 中的 RewriteBase 路徑正確
- 測試階段：`RewriteBase /Final/public/`
- 正式環境：`RewriteBase /`

### 500 錯誤
- 檢查 cPanel Error Log
- 確認 PHP 版本 8.0+
- 確認 .htaccess 語法正確

### 資料庫連線失敗
- 確認資料庫名稱包含 cPanel 前綴
- 確認使用者已授權存取資料庫

### CSS/JS 載入失敗
- 確認 config/app.php 的 url 設定正確
- 檢查瀏覽器 Console 的錯誤訊息
