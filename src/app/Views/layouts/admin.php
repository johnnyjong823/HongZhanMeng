<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? '鴻展盟管理系統') ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= asset('images/favicon/favicon.ico') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= asset('images/favicon/favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= asset('images/favicon/favicon-16x16.png') ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= asset('images/favicon/apple-touch-icon.png') ?>">
    <link rel="manifest" href="<?= asset('images/favicon/site.webmanifest') ?>">
    
    <!-- Tailwind CSS (本地) -->
    <script src="<?= asset('js/tailwind.js') ?>"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#EBF4FB',
                            100: '#D6E9F7',
                            200: '#ADD3EF',
                            300: '#85BDE7',
                            400: '#5CA7DF',
                            500: '#4A90D9',
                            600: '#357ABD',
                            700: '#2E6DA4',
                            800: '#1E4A6F',
                            900: '#0F253A'
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome (本地) -->
    <link rel="stylesheet" href="<?= asset('css/fontawesome.min.css') ?>">
    
    <!-- 自訂樣式 -->
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
    
    <!-- Custom Styles -->
    <style>
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
            --color-primary-hover: #357ABD;
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
        }
        
        body {
            background-color: var(--bg-secondary);
            color: var(--text-primary);
        }
        
        .sidebar {
            transition: transform 0.3s ease-in-out;
        }
        .sidebar.collapsed {
            transform: translateX(-100%);
        }
        @media (min-width: 768px) {
            .sidebar.collapsed {
                transform: translateX(0);
            }
        }
        .menu-item.active {
            background-color: rgba(74, 144, 217, 0.1);
            border-right: 3px solid #4A90D9;
            color: #4A90D9;
        }
        .menu-item:hover {
            background-color: rgba(74, 144, 217, 0.05);
        }
        
        /* 卡片樣式 */
        .card {
            background: #FFFFFF;
            border: 1px solid #E9ECEF;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }
        
        /* 按鈕樣式 */
        .btn-primary {
            background-color: #4A90D9;
            color: #FFFFFF;
            border-radius: 6px;
            transition: background-color 0.2s;
        }
        .btn-primary:hover {
            background-color: #357ABD;
        }
        
        /* 表單樣式 */
        .form-control:focus {
            border-color: #4A90D9;
            box-shadow: 0 0 0 3px rgba(74, 144, 217, 0.15);
        }
    </style>
    
    <?= $headContent ?? '' ?>
</head>
<body class="bg-[#F8F9FA]">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="sidebar fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-sm border-r border-[#E9ECEF] md:relative md:translate-x-0" id="sidebar">
            <!-- Logo -->
            <div class="flex items-center justify-between h-16 px-6 border-b border-[#E9ECEF]">
                <a href="<?= url('/admin') ?>" class="flex items-center">
                    <span class="text-xl font-bold text-[#4A90D9]">鴻展盟</span>
                    <span class="ml-2 text-sm text-[#6C757D]">管理系統</span>
                </a>
                <button class="md:hidden text-gray-500 hover:text-gray-700" onclick="toggleSidebar()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Navigation -->
            <nav class="px-4 py-4 overflow-y-auto h-[calc(100%-4rem)]">
                <ul class="space-y-1">
                    <li>
                        <a href="<?= url('/admin') ?>" class="menu-item flex items-center px-4 py-3 text-[#212529] rounded-lg <?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                            <i class="fas fa-tachometer-alt w-5 mr-3 text-[#6C757D]"></i>
                            儀表板
                        </a>
                    </li>
                    
                    <li class="pt-4">
                        <span class="px-4 text-xs font-semibold text-[#ADB5BD] uppercase">內容管理</span>
                    </li>
                    
                    <li>
                        <a href="<?= url('/admin/products') ?>" class="menu-item flex items-center px-4 py-3 text-[#212529] rounded-lg <?= $currentPage === 'products' ? 'active' : '' ?>">
                            <i class="fas fa-box w-5 mr-3 text-[#6C757D]"></i>
                            產品維護
                        </a>
                    </li>
                    
                    <li>
                        <a href="<?= url('/admin/banners') ?>" class="menu-item flex items-center px-4 py-3 text-[#212529] rounded-lg <?= $currentPage === 'banners' ? 'active' : '' ?>">
                            <i class="fas fa-images w-5 mr-3 text-[#6C757D]"></i>
                            圖片輪播維護
                        </a>
                    </li>
                    
                    <li>
                        <a href="<?= url('/admin/knowledge') ?>" class="menu-item flex items-center px-4 py-3 text-[#212529] rounded-lg <?= $currentPage === 'knowledge' ? 'active' : '' ?>">
                            <i class="fas fa-lightbulb w-5 mr-3 text-[#6C757D]"></i>
                            知識分享維護
                        </a>
                    </li>
                    
                    <li class="pt-4">
                        <span class="px-4 text-xs font-semibold text-[#ADB5BD] uppercase">系統管理</span>
                    </li>
                    
                    <li>
                        <a href="<?= url('/admin/users') ?>" class="menu-item flex items-center px-4 py-3 text-[#212529] rounded-lg <?= $currentPage === 'users' ? 'active' : '' ?>">
                            <i class="fas fa-users w-5 mr-3 text-[#6C757D]"></i>
                            使用者管理
                        </a>
                    </li>
                    
                    <li>
                        <a href="<?= url('/admin/roles') ?>" class="menu-item flex items-center px-4 py-3 text-[#212529] rounded-lg <?= $currentPage === 'roles' ? 'active' : '' ?>">
                            <i class="fas fa-user-shield w-5 mr-3 text-[#6C757D]"></i>
                            角色管理
                        </a>
                    </li>
                    
                    <li>
                        <a href="<?= url('/admin/functions') ?>" class="menu-item flex items-center px-4 py-3 text-[#212529] rounded-lg <?= $currentPage === 'functions' ? 'active' : '' ?>">
                            <i class="fas fa-sitemap w-5 mr-3 text-[#6C757D]"></i>
                            功能管理
                        </a>
                    </li>
                    
                    <li class="pt-4">
                        <span class="px-4 text-xs font-semibold text-[#ADB5BD] uppercase">系統紀錄</span>
                    </li>
                    
                    <li>
                        <a href="<?= url('/admin/action-logs') ?>" class="menu-item flex items-center px-4 py-3 text-[#212529] rounded-lg <?= $currentPage === 'action-logs' ? 'active' : '' ?>">
                            <i class="fas fa-clipboard-list w-5 mr-3 text-[#6C757D]"></i>
                            操作紀錄
                        </a>
                    </li>
                    
                    <li>
                        <a href="<?= url('/admin/login-logs') ?>" class="menu-item flex items-center px-4 py-3 text-[#212529] rounded-lg <?= $currentPage === 'login-logs' ? 'active' : '' ?>">
                            <i class="fas fa-sign-in-alt w-5 mr-3 text-[#6C757D]"></i>
                            登入紀錄
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>
        
        <!-- Mobile sidebar overlay -->
        <div class="fixed inset-0 z-40 bg-black bg-opacity-50 hidden" id="sidebar-overlay" onclick="toggleSidebar()"></div>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="bg-white border-b border-[#E9ECEF] h-16 flex items-center justify-between px-6">
                <div class="flex items-center">
                    <button class="md:hidden text-[#6C757D] hover:text-[#212529] mr-4" onclick="toggleSidebar()">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h1 class="text-lg font-semibold text-[#212529]"><?= htmlspecialchars($title ?? '儀表板') ?></h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button class="flex items-center space-x-2 text-[#212529] hover:text-[#4A90D9]" id="user-menu-btn">
                            <div class="w-8 h-8 bg-[#4A90D9] rounded-full flex items-center justify-center text-white font-semibold">
                                <?php
                                $displayName = $_SESSION['user']['display_name'] ?? 'U';
                                echo htmlspecialchars(function_exists('mb_substr') ? mb_substr($displayName, 0, 1) : substr($displayName, 0, 1));
                                ?>
                            </div>
                            <span class="hidden sm:block"><?= htmlspecialchars($_SESSION['user']['display_name'] ?? '使用者') ?></span>
                            <i class="fas fa-chevron-down text-xs text-[#6C757D]"></i>
                        </button>
                        
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-md border border-[#E9ECEF] py-2 hidden" id="user-menu">
                            <a href="<?= url('/admin/account/profile') ?>" class="block px-4 py-2 text-[#212529] hover:bg-[#F8F9FA]">
                                <i class="fas fa-user mr-2 text-[#6C757D]"></i>個人資料
                            </a>
                            <a href="<?= url('/admin/account/change-password') ?>" class="block px-4 py-2 text-[#212529] hover:bg-[#F8F9FA]">
                                <i class="fas fa-key mr-2 text-[#6C757D]"></i>修改密碼
                            </a>
                            <hr class="my-2 border-[#E9ECEF]">
                            <a href="<?= url('/account/logout') ?>" class="block px-4 py-2 text-[#DC3545] hover:bg-[#F8D7DA]">
                                <i class="fas fa-sign-out-alt mr-2"></i>登出
                            </a>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <?php if (isset($flashMessage)): ?>
                <div class="mb-4 p-4 rounded-lg <?= $flashType === 'success' ? 'bg-[#D4EDDA] text-[#155724]' : 'bg-[#F8D7DA] text-[#721C24]' ?>">
                    <?= htmlspecialchars($flashMessage) ?>
                </div>
                <?php endif; ?>
                
                <?= $content ?>
            </main>
            
            <!-- Footer -->
            <footer class="bg-white border-t border-[#E9ECEF] px-6 py-3 text-center text-sm text-[#6C757D]">
                &copy; <?= date('Y') ?> 鴻展盟. All rights reserved.
            </footer>
        </div>
    </div>
    
    <!-- Scripts -->
    <script>
        // Sidebar Toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('collapsed');
            overlay.classList.toggle('hidden');
        }
        
        // User Menu Toggle
        document.getElementById('user-menu-btn').addEventListener('click', function(e) {
            e.stopPropagation();
            document.getElementById('user-menu').classList.toggle('hidden');
        });
        
        document.addEventListener('click', function() {
            document.getElementById('user-menu').classList.add('hidden');
        });
        
        // CSRF Token
        function getCsrfToken() {
            return '<?= csrf_token() ?>';
        }
        
        // Ajax Helper
        async function api(url, method = 'GET', data = null) {
            const options = {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': getCsrfToken()
                }
            };
            
            if (data) {
                options.body = JSON.stringify(data);
            }
            
            const response = await fetch(url, options);
            return response.json();
        }
        
        // Toast Notification
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            const bgColor = type === 'success' ? 'background-color: #D4EDDA; color: #155724;' : 'background-color: #F8D7DA; color: #721C24;';
            toast.style.cssText = `position: fixed; top: 1rem; right: 1rem; z-index: 50; padding: 0.75rem 1.5rem; border-radius: 6px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); ${bgColor}`;
            toast.textContent = message;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
    </script>
    
    <?= $footerContent ?? '' ?>
</body>
</html>
