# Google Sheet 表單串接設定指南

本文件說明如何設定 Google Sheet 和 Google Apps Script，以接收網站表單提交的資料。

---

## 步驟一：建立 Google Sheet

1. 前往 [Google Sheets](https://sheets.google.com)
2. 點擊「**+**」建立新試算表
3. 將試算表命名為：`鴻展盟表單資料` （或您喜歡的名稱）
4. 在第一列（Row 1）建立以下欄位標題：

| A | B | C | D | E | F | G | H | I | J |
|---|---|---|---|---|---|---|---|---|---|
| 提交時間 | 姓名 | 稱謂 | 諮詢需求 | 參觀時間 | 聯絡電話 | 聯絡時間 | 所在城市 | 家庭成員 | 其他留言 |

---

## 步驟二：建立 Google Apps Script

1. 在 Google Sheet 中，點擊上方選單：**擴充功能 → Apps Script**
2. 這會開啟 Apps Script 編輯器
3. 刪除預設的程式碼，貼上以下程式碼：

```javascript
/**
 * Google Apps Script - 表單資料接收器
 * 用於接收網站表單提交的資料並寫入 Google Sheet，並發送 Email 通知
 */

// ⚠️ 請在此設定接收通知的 Email（可設定多個，用逗號分隔）
const NOTIFICATION_EMAILS = [
  'your-email@example.com',
  // 'another-email@example.com',
  // 'third-email@example.com'
];

// 處理 POST 請求
function doPost(e) {
  try {
    // 取得 Google Sheet
    const sheet = SpreadsheetApp.getActiveSpreadsheet().getActiveSheet();
    
    // 解析傳入的 JSON 資料
    const data = JSON.parse(e.postData.contents);
    
    // ===== 必填欄位驗證（根據諮詢需求動態調整）=====
    const inquiry = data.inquiry || '';
    let requiredFields = [
      { key: 'name', label: '姓名' },
      { key: 'inquiry', label: '諮詢需求' },
      { key: 'phone', label: '聯絡電話' }  // 聯絡電話一律必填
    ];
    
    // 根據諮詢需求添加對應的必填欄位
    if (inquiry === '預約參觀') {
      requiredFields.push({ key: 'visitTime', label: '參觀時間' });
    } else if (inquiry === '電洽諮詢') {
      requiredFields.push({ key: 'contactTime', label: '聯絡時間' });
    }
    
    const missingFields = requiredFields
      .filter(field => !data[field.key] || data[field.key].trim() === '')
      .map(field => field.label);
    
    if (missingFields.length > 0) {
      return ContentService
        .createTextOutput(JSON.stringify({ 
          success: false, 
          message: `請填寫必填欄位：${missingFields.join('、')}` 
        }))
        .setMimeType(ContentService.MimeType.JSON);
    }
    
    // ===== 電話格式驗證（一律驗證）=====
    if (data.phone) {
      const phoneRegex = /^[0-9]{8,10}$/;
      if (!phoneRegex.test(data.phone.replace(/\D/g, ''))) {
        return ContentService
          .createTextOutput(JSON.stringify({ 
            success: false, 
            message: '聯絡電話格式不正確，請輸入 8-10 位數字' 
          }))
          .setMimeType(ContentService.MimeType.JSON);
      }
    }
    
    // 準備要寫入的資料列
    const timestamp = data.timestamp || new Date().toLocaleString('zh-TW');
    const rowData = [
      timestamp,                // 提交時間
      data.name || '',          // 姓名
      data.title || '',         // 稱謂
      data.inquiry || '',       // 諮詢需求
      data.visitTime || '',     // 參觀時間
      data.phone || '',         // 聯絡電話
      data.contactTime || '',   // 聯絡時間
      data.city || '',          // 所在城市
      data.family || '',        // 家庭成員
      data.message || ''        // 其他留言
    ];
    
    // 將資料寫入最後一列
    sheet.appendRow(rowData);
    
    // ===== 發送 Email 通知 =====
    sendNotificationEmail(data, timestamp);
    
    // 回傳成功訊息
    return ContentService
      .createTextOutput(JSON.stringify({ success: true, message: '資料已成功寫入' }))
      .setMimeType(ContentService.MimeType.JSON);
      
  } catch (error) {
    // 回傳錯誤訊息
    return ContentService
      .createTextOutput(JSON.stringify({ success: false, message: error.toString() }))
      .setMimeType(ContentService.MimeType.JSON);
  }
}

/**
 * 發送 Email 通知
 */
function sendNotificationEmail(data, timestamp) {
  // 如果沒有設定 Email，則不發送
  if (!NOTIFICATION_EMAILS || NOTIFICATION_EMAILS.length === 0) return;
  
  const inquiry = data.inquiry || '';
  const customerName = `${data.name || ''}${data.title || ''}`;
  
  // 根據諮詢類型設定主旨標籤
  const typeLabel = inquiry === '預約參觀' ? '📅 預約參觀' : '📞 電洽諮詢';
  const subject = `【Ascenda 愛升達】${typeLabel} - ${customerName}`;
  
  // 建立 HTML 格式的 Email 內容
  const htmlBody = createEmailHtml(data, timestamp, inquiry);
  
  // 建立純文字版本（備用）
  const plainBody = createEmailPlainText(data, timestamp, inquiry);
  
  // 發送給所有指定的 Email
  const recipients = NOTIFICATION_EMAILS.join(',');
  
  MailApp.sendEmail({
    to: recipients,
    subject: subject,
    body: plainBody,
    htmlBody: htmlBody
  });
}

/**
 * 建立 HTML 格式的 Email 內容
 */
function createEmailHtml(data, timestamp, inquiry) {
  const customerName = `${data.name || ''}${data.title || ''}`;
  const typeColor = inquiry === '預約參觀' ? '#2E7D32' : '#1565C0';
  const typeIcon = inquiry === '預約參觀' ? '📅' : '📞';
  
  // 根據諮詢類型顯示不同的資訊區塊
  let detailsHtml = '';
  
  if (inquiry === '預約參觀') {
    detailsHtml = `
      <tr>
        <td style="padding: 12px 16px; border-bottom: 1px solid #eee; color: #666; width: 120px;">參觀時間</td>
        <td style="padding: 12px 16px; border-bottom: 1px solid #eee; color: #333; font-weight: 500;">${data.visitTime || '-'}</td>
      </tr>
    `;
  } else if (inquiry === '電洽諮詢') {
    detailsHtml = `
      <tr>
        <td style="padding: 12px 16px; border-bottom: 1px solid #eee; color: #666; width: 120px;">聯絡電話</td>
        <td style="padding: 12px 16px; border-bottom: 1px solid #eee; color: #333; font-weight: 500;">
          <a href="tel:${data.phone || ''}" style="color: #1565C0; text-decoration: none;">${data.phone || '-'}</a>
        </td>
      </tr>
      <tr>
        <td style="padding: 12px 16px; border-bottom: 1px solid #eee; color: #666; width: 120px;">聯絡時間</td>
        <td style="padding: 12px 16px; border-bottom: 1px solid #eee; color: #333; font-weight: 500;">${data.contactTime || '-'}</td>
      </tr>
    `;
  }
  
  return `
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; background-color: #f5f5f5; font-family: 'Microsoft JhengHei', 'Noto Sans TC', Arial, sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f5f5f5; padding: 40px 20px;">
    <tr>
      <td align="center">
        <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
          
          <!-- Header -->
          <tr>
            <td style="background: linear-gradient(135deg, #1a1a1a 0%, #333 100%); padding: 32px 40px; text-align: center;">
              <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 500; letter-spacing: 2px;">
                Ascenda 愛升達
              </h1>
              <p style="margin: 8px 0 0; color: #ccc; font-size: 14px;">新的諮詢表單通知</p>
            </td>
          </tr>
          
          <!-- Type Badge -->
          <tr>
            <td style="padding: 24px 40px 0;">
              <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td>
                    <span style="display: inline-block; background-color: ${typeColor}; color: #fff; padding: 8px 20px; border-radius: 20px; font-size: 14px; font-weight: 500;">
                      ${typeIcon} ${inquiry}
                    </span>
                  </td>
                  <td style="text-align: right; color: #999; font-size: 13px;">
                    ${timestamp}
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          
          <!-- Customer Info -->
          <tr>
            <td style="padding: 24px 40px;">
              <h2 style="margin: 0 0 20px; color: #333; font-size: 20px; font-weight: 600; border-bottom: 2px solid #eee; padding-bottom: 12px;">
                👤 客戶資訊
              </h2>
              <table width="100%" cellpadding="0" cellspacing="0" style="border: 1px solid #eee; border-radius: 8px; overflow: hidden;">
                <tr>
                  <td style="padding: 12px 16px; border-bottom: 1px solid #eee; color: #666; width: 120px;">姓名</td>
                  <td style="padding: 12px 16px; border-bottom: 1px solid #eee; color: #333; font-weight: 500;">${customerName}</td>
                </tr>
                <tr>
                  <td style="padding: 12px 16px; border-bottom: 1px solid #eee; color: #666;">諮詢需求</td>
                  <td style="padding: 12px 16px; border-bottom: 1px solid #eee; color: #333; font-weight: 500;">${inquiry}</td>
                </tr>
                ${detailsHtml}
                <tr>
                  <td style="padding: 12px 16px; border-bottom: 1px solid #eee; color: #666;">所在城市</td>
                  <td style="padding: 12px 16px; border-bottom: 1px solid #eee; color: #333;">${data.city || '-'}</td>
                </tr>
                <tr>
                  <td style="padding: 12px 16px; color: #666;">家庭成員</td>
                  <td style="padding: 12px 16px; color: #333;">${data.family ? data.family + ' 位' : '-'}</td>
                </tr>
              </table>
            </td>
          </tr>
          
          <!-- Message -->
          ${data.message ? `
          <tr>
            <td style="padding: 0 40px 24px;">
              <h2 style="margin: 0 0 12px; color: #333; font-size: 16px; font-weight: 600;">
                💬 其他留言
              </h2>
              <div style="background-color: #f9f9f9; border-left: 4px solid #ddd; padding: 16px 20px; border-radius: 0 8px 8px 0; color: #555; line-height: 1.6;">
                ${data.message}
              </div>
            </td>
          </tr>
          ` : ''}
          
          <!-- Footer -->
          <tr>
            <td style="background-color: #fafafa; padding: 24px 40px; text-align: center; border-top: 1px solid #eee;">
              <p style="margin: 0; color: #999; font-size: 13px;">
                此郵件由系統自動發送，請勿直接回覆
              </p>
              <p style="margin: 8px 0 0; color: #666; font-size: 13px;">
                鴻展盟科技 | Ascenda 愛升達家用電梯
              </p>
            </td>
          </tr>
          
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
  `;
}

/**
 * 建立純文字格式的 Email 內容（備用）
 */
function createEmailPlainText(data, timestamp, inquiry) {
  const customerName = `${data.name || ''}${data.title || ''}`;
  
  let details = '';
  if (inquiry === '預約參觀') {
    details = `參觀時間：${data.visitTime || '-'}`;
  } else if (inquiry === '電洽諮詢') {
    details = `聯絡電話：${data.phone || '-'}\n聯絡時間：${data.contactTime || '-'}`;
  }
  
  return `
═══════════════════════════════════════
    Ascenda 愛升達 - 新的諮詢表單
═══════════════════════════════════════

【${inquiry}】

提交時間：${timestamp}

───────────────────────────────────────
客戶資訊
───────────────────────────────────────

姓　　名：${customerName}
諮詢需求：${inquiry}
${details}
所在城市：${data.city || '-'}
家庭成員：${data.family ? data.family + ' 位' : '-'}

${data.message ? `───────────────────────────────────────
其他留言
───────────────────────────────────────

${data.message}` : ''}

═══════════════════════════════════════
此郵件由系統自動發送，請勿直接回覆
鴻展盟科技 | Ascenda 愛升達家用電梯
═══════════════════════════════════════
  `;
}

// 處理 GET 請求（用於測試）
function doGet(e) {
  return ContentService
    .createTextOutput(JSON.stringify({ status: 'OK', message: 'Google Apps Script 運作正常' }))
    .setMimeType(ContentService.MimeType.JSON);
}

// 測試函式 - 預約參觀（含發送 Email）
function testDoPost_Visit() {
  const testData = {
    postData: {
      contents: JSON.stringify({
        timestamp: new Date().toLocaleString('zh-TW'),
        name: '測試用戶',
        title: '先生',
        inquiry: '預約參觀',
        visitTime: '平日10:00-12:00',
        phone: '',
        contactTime: '',
        city: '台北市',
        family: '2',
        message: '這是測試留言，想了解電梯的詳細規格。'
      })
    }
  };
  
  const result = doPost(testData);
  Logger.log(result.getContent());
}

// 測試函式 - 電洽諮詢（含發送 Email）
function testDoPost_Call() {
  const testData = {
    postData: {
      contents: JSON.stringify({
        timestamp: new Date().toLocaleString('zh-TW'),
        name: '測試用戶',
        title: '小姐',
        inquiry: '電洽諮詢',
        visitTime: '',
        phone: '0912345678',
        contactTime: '平日9:00-11:00',
        city: '新北市',
        family: '3',
        message: '想了解價格和安裝時間'
      })
    }
  };
  
  const result = doPost(testData);
  Logger.log(result.getContent());
}

// 測試僅發送 Email（不寫入 Sheet）
function testSendEmail() {
  const testData = {
    name: '王小明',
    title: '先生',
    inquiry: '預約參觀',
    visitTime: '平日13:00-15:00',
    phone: '',
    contactTime: '',
    city: '台中市',
    family: '4',
    message: '家中有長輩，想了解電梯的安全性和操作方式。'
  };
  
  sendNotificationEmail(testData, new Date().toLocaleString('zh-TW'));
  Logger.log('測試 Email 已發送');
}

// 測試驗證失敗情況
function testValidationFail() {
  const testData = {
    postData: {
      contents: JSON.stringify({
        timestamp: new Date().toLocaleString('zh-TW'),
        name: '',  // 空的姓名
        title: '先生',
        inquiry: '',  // 空的諮詢需求
        visitTime: '平日10:00-12:00',
        phone: '0912345678',
        contactTime: '平日9:00-11:00',
        city: '台北市',
        family: '2',
        message: ''
      })
    }
  };
  
  const result = doPost(testData);
  Logger.log(result.getContent());
}
```

4. 點擊上方的「**儲存**」按鈕（磁碟圖示）或按 `Ctrl + S`
5. 將專案命名為：`表單接收器`

---

## 步驟三：部署為網路應用程式

1. 在 Apps Script 編輯器中，點擊右上角的「**部署**」→「**新增部署作業**」

2. 點擊左側「**選取類型**」旁的齒輪圖示，選擇「**網頁應用程式**」

3. 設定部署選項：
   - **說明**：`表單資料接收 v1`
   - **執行身分**：`我`（您的帳號）
   - **具有存取權的使用者**：`所有人`

4. 點擊「**部署**」

5. 首次部署會要求授權：
   - 點擊「**授予存取權**」
   - 選擇您的 Google 帳號
   - 如出現「這個應用程式未經 Google 驗證」警告，點擊「**進階**」→「**前往 表單接收器（不安全）**」
   - 點擊「**允許**」

6. 部署完成後，複製「**網頁應用程式網址**」
   - 網址格式類似：`https://script.google.com/macros/s/AKfycb.../exec`

---

## 步驟四：更新網站程式碼

1. 開啟 `src/js/main.js`

2. 找到以下這行程式碼：
   ```javascript
   const GOOGLE_SCRIPT_URL = 'YOUR_GOOGLE_APPS_SCRIPT_WEB_APP_URL';
   ```

3. 將 `YOUR_GOOGLE_APPS_SCRIPT_WEB_APP_URL` 替換為您在步驟三複製的網址，例如：
   ```javascript
   const GOOGLE_SCRIPT_URL = 'https://script.google.com/macros/s/AKfycbyXXXXXXXX.../exec';
   ```

4. 儲存檔案

---

## 步驟五：測試

1. 在瀏覽器中開啟您的網站
2. 填寫聯絡表單並提交
3. 檢查 Google Sheet 是否有新增資料

---

## 常見問題

### Q1: 提交後沒有收到資料？

- 確認 Google Apps Script 已正確部署
- 確認 `GOOGLE_SCRIPT_URL` 已正確設定
- 開啟瀏覽器開發者工具（F12）檢查 Console 是否有錯誤訊息

### Q2: 出現 CORS 錯誤？

程式碼已使用 `mode: 'no-cors'` 模式，應不會出現此問題。如仍有問題，請確認：
- 部署時「具有存取權的使用者」設為「所有人」
- 已完成 Google 帳號授權

### Q3: 如何更新 Apps Script 程式碼？

1. 修改 Apps Script 程式碼
2. 點擊「部署」→「管理部署作業」
3. 點擊編輯圖示（鉛筆）
4. 在「版本」下拉選單選擇「新版本」
5. 點擊「部署」

### Q4: 如何新增更多欄位？

1. 在 Google Sheet 新增欄位標題
2. 修改 Apps Script 的 `rowData` 陣列
3. 修改 `main.js` 的 `payload` 物件
4. 重新部署 Apps Script

---

## 進階設定

### Email 通知設定

程式碼已內建 Email 通知功能，只需修改程式碼最上方的 `NOTIFICATION_EMAILS` 陣列即可：

```javascript
// ⚠️ 請在此設定接收通知的 Email（可設定多個，用逗號分隔）
const NOTIFICATION_EMAILS = [
  'your-email@example.com',
  'another-email@example.com',
  'third-email@example.com'
];
```

**Email 功能說明：**
- 支援多個收件人
- 根據諮詢類型（預約參觀/電洽諮詢）顯示不同資訊
- 同時包含 HTML 美觀版本和純文字版本
- 電話號碼可直接點擊撥打

### 測試 Email 發送

在 Apps Script 編輯器中執行 `testSendEmail` 函式可測試 Email 發送功能（不會寫入 Sheet）。

---

## 檔案說明

| 檔案 | 說明 |
|------|------|
| `src/js/main.js` | 前端表單提交邏輯 |
| `Google Apps Script` | 後端資料處理（在 Google 雲端） |
| `Google Sheet` | 資料儲存位置 |

---

## 必填欄位驗證

前端（main.js）和後端（Google Apps Script）都會根據「諮詢需求」動態驗證必填欄位：

### 基本必填欄位（所有情況）

| 欄位 | 表單 name 屬性 | Apps Script key |
|------|---------------|-----------------|
| 姓名 | `name` | `name` |
| 諮詢需求 | `inquiry` | `inquiry` |
| 聯絡電話 | `phone` | `phone` |

### 預約參觀 - 額外必填欄位

| 欄位 | 表單 name 屬性 | Apps Script key |
|------|---------------|-----------------|
| 參觀時間 | `visit-time` | `visitTime` |

### 電洽諮詢 - 額外必填欄位

| 欄位 | 表單 name 屬性 | Apps Script key |
|------|---------------|-----------------|
| 聯絡時間 | `contact-time` | `contactTime` |

### 時間選項

**參觀時間：**
- 平日10:00 - 12:00
- 平日13:00 - 15:00
- 平日16:00 - 18:00

**聯絡時間：**
- 平日9:00 - 11:00
- 平日13:00 - 15:00
- 平日16:00 - 18:00
- 平日19:00 - 20:00

---

**完成以上步驟後，您的網站表單就可以自動將資料寫入 Google Sheet 了！**
