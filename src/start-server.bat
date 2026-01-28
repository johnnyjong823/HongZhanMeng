@echo off
chcp 65001 >nul
echo =============================================
echo  鴻展盟管理系統 - 開發伺服器
echo =============================================
echo.

REM 設定 PHP 路徑 (WinGet 安裝位置)
set "PHP_PATH=%LOCALAPPDATA%\Microsoft\WinGet\Packages\PHP.PHP.8.3_Microsoft.Winget.Source_8wekyb3d8bbwe"
set "PATH=%PHP_PATH%;%PATH%"

echo 啟動中...
echo 伺服器網址: http://localhost:8801
echo 按 Ctrl+C 停止伺服器
echo.
"%PHP_PATH%\php.exe" -d upload_max_filesize=100M -d post_max_size=120M -d max_execution_time=300 -d max_input_time=300 -d memory_limit=256M -S localhost:8801 -t public
pause
