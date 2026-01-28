<?php
namespace App\Services;

class MailService
{
    protected $config;
    
    public function __construct()
    {
        $this->config = require ROOT_PATH . '/config/mail.php';
    }
    
    /**
     * 發送密碼重設郵件
     */
    public function sendPasswordReset($email, $name, $resetUrl)
    {
        $subject = '密碼重設 - 鴻展盟管理系統';
        $body = $this->getPasswordResetTemplate($name, $resetUrl);
        
        return $this->send($email, $subject, $body);
    }
    
    /**
     * 發送郵件
     */
    public function send($to, $subject, $body, $isHtml = true)
    {
        $headers = [
            'MIME-Version: 1.0',
            'Content-Type: ' . ($isHtml ? 'text/html' : 'text/plain') . '; charset=UTF-8',
            'From: ' . $this->config['from_name'] . ' <' . $this->config['from_email'] . '>',
            'Reply-To: ' . $this->config['from_email'],
            'X-Mailer: PHP/' . phpversion()
        ];
        
        $result = @mail($to, $subject, $body, implode("\r\n", $headers));
        
        // 記錄郵件發送
        $this->logMail($to, $subject, $result);
        
        return $result;
    }
    
    /**
     * 密碼重設郵件模板
     */
    protected function getPasswordResetTemplate($name, $resetUrl)
    {
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #667eea;">密碼重設</h2>
        
        <p>您好，{$name}</p>
        
        <p>我們收到了您的密碼重設請求。請點擊下方按鈕重設您的密碼：</p>
        
        <p style="text-align: center;">
            <a href="{$resetUrl}" 
               style="display: inline-block; padding: 12px 24px; 
                      background-color: #667eea; color: white; 
                      text-decoration: none; border-radius: 5px;
                      font-weight: bold;">
                重設密碼
            </a>
        </p>
        
        <p>如果按鈕無法點擊，請複製以下連結到瀏覽器：</p>
        <p style="word-break: break-all; color: #666;">
            <a href="{$resetUrl}">{$resetUrl}</a>
        </p>
        
        <p><strong>此連結將在 1 小時後失效。</strong></p>
        
        <p>如果您沒有申請密碼重設，請忽略此郵件。</p>
        
        <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">
        
        <p style="color: #999; font-size: 12px;">
            此郵件由系統自動發送，請勿直接回覆。<br>
            鴻展盟管理系統
        </p>
    </div>
</body>
</html>
HTML;
    }
    
    /**
     * 記錄郵件發送
     */
    protected function logMail($to, $subject, $success)
    {
        $logDir = ROOT_PATH . '/storage/logs';
        
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $logPath = $logDir . '/mail.log';
        $status = $success ? 'SUCCESS' : 'FAILED';
        $log = sprintf(
            "[%s] %s | To: %s | Subject: %s\n",
            date('Y-m-d H:i:s'),
            $status,
            $to,
            $subject
        );
        
        file_put_contents($logPath, $log, FILE_APPEND);
    }
}
