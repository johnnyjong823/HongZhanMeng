<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= htmlspecialchars($description ?? 'Cibes 愛升達家用電梯 - 源自瑞典的高效能質感電梯，跨越年齡想像，邁出自由步伐') ?>">
    <meta name="keywords" content="<?= htmlspecialchars($keywords ?? 'Cibes, 電梯, 家用電梯, 瑞典電梯, 愛升達, 鴻展盟') ?>">
    <title><?= htmlspecialchars($title ?? 'Cibes 愛升達家用電梯 | 鴻展盟科技') ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= asset('images/favicon/favicon.ico') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= asset('images/favicon/favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= asset('images/favicon/favicon-16x16.png') ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= asset('images/favicon/apple-touch-icon.png') ?>">
    <link rel="manifest" href="<?= asset('images/favicon/site.webmanifest') ?>">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?= asset('css/frontend/reset.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/frontend/variables.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/frontend/base.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/frontend/components.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/frontend/sections.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/frontend/responsive.css') ?>">
    
    <?= $headContent ?? '' ?>
</head>
<body>
    <!-- Header / Navigation -->
    <header class="header" id="header">
        <div class="header-inner">
            <a href="<?= url('/') ?>" class="logo">
                <img src="<?= asset('images/frontend/Logo.png') ?>" alt="Cibes Logo">
            </a>
            
            <!-- Main Navigation -->
            <nav class="main-nav">
                <ul class="nav-menu">
                    <li class="nav-item has-dropdown">
                        <a href="<?= url('/products/ascenda') ?>" class="nav-link">產品介紹</a>
                        <ul class="dropdown-menu">
                            <li class="has-submenu">
                                <a href="<?= url('/products/ascenda') ?>">Ascenda 愛升達</a>
                                <ul class="submenu">
                                    <li><a href="<?= url('/products/dimensions') ?>">尺寸與安裝</a></li>
                                    <li><a href="<?= url('/products/dimensions#tech-resources') ?>">技術資料</a></li>
                                    <li><a href="<?= url('/products/faq') ?>">產品 Q&A</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="<?= url('/about/hongzhanmeng') ?>" class="nav-link">關於鴻展盟</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= url('/about/cibes') ?>" class="nav-link">關於Cibes</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= url('/knowledge') ?>" class="nav-link">知識分享</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= url('/contact-us') ?>" class="nav-link">聯繫我們</a>
                    </li>
                </ul>
            </nav>
            
            <!-- Mobile Menu Toggle -->
            <button class="menu-toggle" id="menuToggle" aria-label="開啟選單">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-inner">
            <div class="footer-item footer-brand">
                <a href="https://www.facebook.com/profile.php?id=61584790207672" target="_blank" class="footer-social">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </a>
                <div class="footer-brand-box">
                    <span class="footer-brand-text">鴻展盟科技 Ascenda</span>
                    <svg class="footer-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.35-4.35"/>
                    </svg>
                </div>
            </div>
            <a href="https://maps.app.goo.gl/ZrMoZ6SpCipx7Xe59" target="_blank" class="footer-item footer-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                    <circle cx="12" cy="10" r="3"/>
                </svg>
                <span>114 臺北市內湖區星雲街42號</span>
            </a>
            <a href="tel:02-2796-8855" class="footer-item footer-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                </svg>
                <span>02 2796 8855</span>
            </a>
            <a href="mailto:Info@gdalift.com.tw" class="footer-item footer-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                    <polyline points="22,6 12,13 2,6"/>
                </svg>
                <span>Info@gdalift.com.tw</span>
            </a>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <a href="#" class="back-to-top" id="back-to-top" aria-label="返回頂部">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="18 15 12 9 6 15"></polyline>
        </svg>
    </a>

    <!-- JavaScript -->
    <script src="<?= asset('js/frontend.js') ?>"></script>
    
    <?= $footerContent ?? '' ?>
</body>
</html>
