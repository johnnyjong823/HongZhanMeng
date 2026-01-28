<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - 伺服器錯誤</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#F8F9FA]">
    <div class="min-h-screen flex items-center justify-center">
        <div class="text-center">
            <div class="text-[#DC3545] text-6xl mb-4">
                <i class="fas fa-server"></i>
            </div>
            <h1 class="text-6xl font-bold text-[#212529] mb-2">500</h1>
            <h2 class="text-xl text-[#6C757D] mb-4">伺服器錯誤</h2>
            <p class="text-[#6C757D] mb-8">抱歉，系統發生錯誤，請稍後再試</p>
            <div class="space-x-4">
                <a href="<?= url('/') ?>" class="inline-block px-6 py-3 bg-[#4A90D9] text-white rounded-lg hover:bg-[#357ABD]">
                    <i class="fas fa-home mr-2"></i>返回首頁
                </a>
                <a href="javascript:location.reload()" class="inline-block px-6 py-3 bg-[#F8F9FA] text-[#212529] rounded-lg hover:bg-[#E9ECEF] border border-[#DEE2E6]">
                    <i class="fas fa-redo mr-2"></i>重新整理
                </a>
            </div>
        </div>
    </div>
</body>
</html>
