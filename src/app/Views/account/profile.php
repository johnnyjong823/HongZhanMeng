<?php $currentPage = 'dashboard'; ?>

<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center space-x-2 text-sm text-[#6C757D] mb-2">
            <a href="<?= url('/admin') ?>" class="hover:text-[#4A90D9]">儀表板</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-[#212529]">個人資料</span>
        </div>
        <h2 class="text-2xl font-bold text-[#212529]">個人資料</h2>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm">
        <form id="profile-form" class="p-6 space-y-6">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            
            <!-- Profile Picture -->
            <div class="flex items-center space-x-4">
                <div class="w-20 h-20 bg-[#4A90D9] rounded-full flex items-center justify-center text-white text-3xl font-bold">
                    <?php $displayName = $_SESSION['user']['display_name'] ?? 'U'; echo function_exists('mb_substr') ? mb_substr($displayName, 0, 1) : substr($displayName, 0, 1); ?>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-[#212529]"><?= htmlspecialchars($_SESSION['user']['display_name'] ?? '') ?></h3>
                    <p class="text-sm text-[#6C757D]">@<?= htmlspecialchars($_SESSION['user']['username'] ?? '') ?></p>
                </div>
            </div>
            
            <hr class="border-[#E9ECEF]">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Username (Read-only) -->
                <div>
                    <label class="block text-sm font-medium text-[#212529] mb-1">帳號</label>
                    <input type="text" value="<?= htmlspecialchars($_SESSION['user']['username'] ?? '') ?>" disabled
                           class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg bg-[#F8F9FA] text-[#6C757D]">
                    <p class="mt-1 text-xs text-[#6C757D]">帳號無法修改</p>
                </div>
                
                <!-- Display Name -->
                <div>
                    <label for="display_name" class="block text-sm font-medium text-[#212529] mb-1">顯示名稱</label>
                    <input type="text" id="display_name" name="display_name" 
                           value="<?= htmlspecialchars($_SESSION['user']['display_name'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                           placeholder="請輸入顯示名稱">
                </div>
                
                <!-- Email -->
                <div class="md:col-span-2">
                    <label for="email" class="block text-sm font-medium text-[#212529] mb-1">Email</label>
                    <input type="email" id="email" name="email" 
                           value="<?= htmlspecialchars($_SESSION['user']['email'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                           placeholder="請輸入 Email">
                </div>
            </div>
            
            <div id="message" class="hidden p-4 rounded-lg"></div>
            
            <div class="flex justify-between items-center pt-4 border-t border-[#E9ECEF]">
                <a href="<?= url('/admin/account/change-password') ?>" class="text-[#4A90D9] hover:text-[#357ABD]">
                    <i class="fas fa-key mr-2"></i>修改密碼
                </a>
                <button type="submit" id="submit-btn" class="px-6 py-2 bg-[#4A90D9] text-white rounded-lg hover:bg-[#357ABD]">
                    <span id="btn-text">儲存變更</span>
                    <span id="btn-loading" class="hidden">
                        <i class="fas fa-spinner fa-spin mr-2"></i>處理中...
                    </span>
                </button>
            </div>
        </form>
    </div>
    
    <!-- Account Info -->
    <div class="mt-6 bg-white rounded-lg border border-[#E9ECEF] shadow-sm p-6">
        <h3 class="text-lg font-semibold text-[#212529] mb-4">帳號資訊</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div>
                <span class="text-[#6C757D]">最後登入：</span>
                <span class="text-[#212529]"><?= $_SESSION['user']['last_login_at'] ?? '-' ?></span>
            </div>
            <div>
                <span class="text-[#6C757D]">登入 IP：</span>
                <span class="text-[#212529]"><?= $_SESSION['user']['last_login_ip'] ?? '-' ?></span>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('profile-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('submit-btn');
        const btnText = document.getElementById('btn-text');
        const btnLoading = document.getElementById('btn-loading');
        const message = document.getElementById('message');
        
        submitBtn.disabled = true;
        btnText.classList.add('hidden');
        btnLoading.classList.remove('hidden');
        message.classList.add('hidden');
        
        try {
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            
            const response = await fetch('<?= url('/admin/account/update-profile') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            message.textContent = result.message;
            message.classList.remove('hidden', 'bg-[#F8D7DA]', 'text-[#721C24]', 'bg-[#D4EDDA]', 'text-[#155724]');
            
            if (result.success) {
                message.classList.add('bg-[#D4EDDA]', 'text-[#155724]');
                setTimeout(() => location.reload(), 1500);
            } else {
                message.classList.add('bg-[#F8D7DA]', 'text-[#721C24]');
            }
        } catch (error) {
            message.textContent = '發生錯誤，請稍後再試';
            message.classList.remove('hidden');
            message.classList.add('bg-[#F8D7DA]', 'text-[#721C24]');
        } finally {
            submitBtn.disabled = false;
            btnText.classList.remove('hidden');
            btnLoading.classList.add('hidden');
        }
    });
</script>
