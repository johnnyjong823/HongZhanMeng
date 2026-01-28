# PHP 前端設計規範

## 設計風格：白色商務風格

本專案採用專業的白色商務風格設計，強調簡潔、清晰、專業的視覺體驗。

---

## 🎨 色彩規範

### 主色調
| 用途 | 顏色 | HEX | 說明 |
|------|------|-----|------|
| 背景色 | 純白 | `#FFFFFF` | 主要背景 |
| 次要背景 | 淺灰白 | `#F8F9FA` | 卡片、區塊背景 |
| 邊框色 | 淺灰 | `#E9ECEF` | 分隔線、邊框 |

### 文字顏色
| 用途 | 顏色 | HEX | 說明 |
|------|------|-----|------|
| 主要文字 | 深灰黑 | `#212529` | 標題、重要內容 |
| 次要文字 | 中灰 | `#6C757D` | 說明文字、輔助資訊 |
| 輕量文字 | 淺灰 | `#ADB5BD` | 提示、placeholder |

### 功能色彩（淺色系）
| 用途 | 背景色 | 文字色 | 說明 |
|------|--------|--------|------|
| 主要按鈕 | `#4A90D9` | `#FFFFFF` | 主要操作 |
| 成功 | `#D4EDDA` | `#155724` | 成功訊息 |
| 警告 | `#FFF3CD` | `#856404` | 警告訊息 |
| 錯誤 | `#F8D7DA` | `#721C24` | 錯誤訊息 |
| 資訊 | `#D1ECF1` | `#0C5460` | 提示訊息 |

### 懸停狀態
| 元素 | 原始 | 懸停 |
|------|------|------|
| 按鈕 | `#4A90D9` | `#357ABD` |
| 連結 | `#4A90D9` | `#2E6DA4` |
| 表格行 | `#FFFFFF` | `#F5F5F5` |

---

## 📐 間距與排版規範

### 間距系統（8px 基礎單位）
```
xs: 4px   (0.25rem)
sm: 8px   (0.5rem)
md: 16px  (1rem)
lg: 24px  (1.5rem)
xl: 32px  (2rem)
xxl: 48px (3rem)
```

### 字體大小
```
h1: 2rem (32px)
h2: 1.75rem (28px)
h3: 1.5rem (24px)
h4: 1.25rem (20px)
h5: 1rem (16px)
body: 0.875rem (14px)
small: 0.75rem (12px)
```

### 行高
- 標題：1.2 - 1.4
- 內文：1.5 - 1.6

---

## 🧩 元件設計規範

### 卡片 (Card)
```css
.card {
    background: #FFFFFF;
    border: 1px solid #E9ECEF;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    padding: 1.5rem;
}
```

### 按鈕 (Button)
```css
/* 主要按鈕 */
.btn-primary {
    background: #4A90D9;
    color: #FFFFFF;
    border: none;
    border-radius: 6px;
    padding: 0.5rem 1rem;
    font-weight: 500;
}

/* 次要按鈕 */
.btn-secondary {
    background: #F8F9FA;
    color: #212529;
    border: 1px solid #DEE2E6;
    border-radius: 6px;
    padding: 0.5rem 1rem;
}

/* 危險按鈕 */
.btn-danger {
    background: #DC3545;
    color: #FFFFFF;
    border: none;
    border-radius: 6px;
}
```

### 表單元素
```css
.form-control {
    background: #FFFFFF;
    border: 1px solid #CED4DA;
    border-radius: 6px;
    padding: 0.5rem 0.75rem;
    color: #212529;
}

.form-control:focus {
    border-color: #4A90D9;
    box-shadow: 0 0 0 3px rgba(74, 144, 217, 0.15);
}

.form-label {
    color: #212529;
    font-weight: 500;
    margin-bottom: 0.5rem;
}
```

### 表格
```css
.table {
    background: #FFFFFF;
    border-collapse: collapse;
}

.table th {
    background: #F8F9FA;
    color: #212529;
    font-weight: 600;
    padding: 0.75rem 1rem;
    border-bottom: 2px solid #DEE2E6;
}

.table td {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #E9ECEF;
    color: #212529;
}

.table tr:hover {
    background: #F5F5F5;
}
```

### 導航列
```css
.navbar {
    background: #FFFFFF;
    border-bottom: 1px solid #E9ECEF;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.nav-link {
    color: #6C757D;
    padding: 0.5rem 1rem;
}

.nav-link:hover,
.nav-link.active {
    color: #212529;
}
```

---

## 📝 PHP View 撰寫規範

### 檔案結構
```php
<?php
/**
 * 頁面名稱
 * 
 * @var array $data 傳入的資料
 */

// 設定頁面變數
$pageTitle = $data['title'] ?? '預設標題';
?>

<!-- 頁面內容 -->
<div class="container">
    <!-- 使用語意化的 HTML 標籤 -->
</div>
```

### 命名規範
- View 檔案使用 `kebab-case`：`user-list.php`、`create-form.php`
- CSS 類別使用 `kebab-case`：`card-header`、`btn-primary`
- ID 使用 `camelCase`：`userTable`、`submitButton`

### PHP 與 HTML 混合規範
```php
<!-- ✅ 推薦：使用替代語法 -->
<?php if ($condition): ?>
    <div class="content">
        <?= htmlspecialchars($data) ?>
    </div>
<?php endif; ?>

<!-- ❌ 避免：使用大括號 -->
<?php if ($condition) { ?>
    <div class="content">
        <?php echo $data; ?>
    </div>
<?php } ?>
```

### 輸出轉義
```php
<!-- 所有使用者輸入的資料必須轉義 -->
<?= htmlspecialchars($userInput, ENT_QUOTES, 'UTF-8') ?>

<!-- 使用輔助函式簡化 -->
<?= e($userInput) ?>
```

---

## 🎯 設計原則

### 1. 一致性
- 全站使用統一的色彩、間距、字體
- 相同功能的元件外觀保持一致
- 互動行為（hover、focus）保持一致

### 2. 清晰層次
- 使用視覺層次引導使用者注意力
- 重要內容使用較深顏色或較大字體
- 次要內容使用較淺顏色或較小字體

### 3. 適當留白
- 元素間保持足夠間距
- 不要過度擁擠內容
- 使用留白創造視覺呼吸空間

### 4. 響應式設計
- 使用相對單位（rem、%）
- 確保在不同螢幕尺寸下正常顯示
- 移動裝置優先考慮觸控操作

### 5. 無障礙設計
- 確保足夠的顏色對比度（WCAG 2.1 AA 標準）
- 使用語意化 HTML 標籤
- 表單元素必須有 label
- 圖片必須有 alt 屬性

---

## 📋 檢查清單

在提交前端程式碼前，請確認：

- [ ] 背景色使用白色或淺灰色系
- [ ] 文字顏色使用深色（#212529 或 #6C757D）
- [ ] 功能色彩使用淺色背景搭配深色文字
- [ ] 元件樣式符合上述規範
- [ ] 間距使用 8px 基礎單位的倍數
- [ ] PHP 輸出已正確轉義
- [ ] HTML 結構語意化
- [ ] 響應式設計已測試
- [ ] 無 console 錯誤

---

## 🔧 常用 CSS 變數

建議在專案中定義以下 CSS 變數：

```css
:root {
    /* 背景色 */
    --bg-primary: #FFFFFF;
    --bg-secondary: #F8F9FA;
    --bg-tertiary: #E9ECEF;
    
    /* 文字色 */
    --text-primary: #212529;
    --text-secondary: #6C757D;
    --text-muted: #ADB5BD;
    
    /* 功能色 */
    --color-primary: #4A90D9;
    --color-success: #28A745;
    --color-warning: #FFC107;
    --color-danger: #DC3545;
    --color-info: #17A2B8;
    
    /* 邊框 */
    --border-color: #DEE2E6;
    --border-radius: 6px;
    
    /* 陰影 */
    --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.08);
    --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
    
    /* 間距 */
    --spacing-xs: 0.25rem;
    --spacing-sm: 0.5rem;
    --spacing-md: 1rem;
    --spacing-lg: 1.5rem;
    --spacing-xl: 2rem;
}
```
