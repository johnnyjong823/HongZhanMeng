<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? '鴻展盟') ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= asset('images/favicon/favicon.ico') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= asset('images/favicon/favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= asset('images/favicon/favicon-16x16.png') ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= asset('images/favicon/apple-touch-icon.png') ?>">
    <link rel="manifest" href="<?= asset('images/favicon/site.webmanifest') ?>">
    
    <!-- Tailwind CSS (本地) -->
    <script src="<?= asset('js/tailwind.js') ?>"></script>
    
    <!-- Font Awesome (本地) -->
    <link rel="stylesheet" href="<?= asset('css/fontawesome.min.css') ?>">
    
    <!-- 自訂樣式 -->
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
    
    <?= $headContent ?? '' ?>
</head>
<body class="bg-gray-100">
    <?= $content ?>
    
    <?= $footerContent ?? '' ?>
</body>
</html>
