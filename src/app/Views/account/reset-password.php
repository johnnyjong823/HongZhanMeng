<div class="min-h-screen flex items-center justify-center bg-[#F8F9FA] py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-xl border border-[#E9ECEF] shadow-sm">
        <div class="text-center">
            <h1 class="text-3xl font-bold text-[#212529]">重設密碼</h1>
            <p class="mt-2 text-sm text-[#6C757D]">請輸入您的新密碼</p>
        </div>
        
        <form id="reset-form" class="mt-8 space-y-6">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <input type="hidden" name="email" value="<?= htmlspecialchars($email ?? '') ?>">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? '') ?>">
            
            <div>
                <label for="password" class="block text-sm font-medium text-[#212529]">新密碼</label>
                <div class="mt-1 relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-[#ADB5BD]">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" id="password" name="password" required
                           class="pl-10 w-full px-4 py-3 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                           placeholder="請輸入新密碼">
                </div>
                <p class="mt-1 text-xs text-[#6C757D]">密碼至少 8 個字元，須包含英文和數字</p>
            </div>
            
            <div>
                <label for="confirm_password" class="block text-sm font-medium text-[#212529]">確認新密碼</label>
                <div class="mt-1 relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-[#ADB5BD]">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" id="confirm_password" name="confirm_password" required
                           class="pl-10 w-full px-4 py-3 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                           placeholder="請再次輸入新密碼">
                </div>
            </div>
            
            <div id="message" class="hidden p-3 rounded-lg text-sm"></div>
            
            <button type="submit" id="submit-btn"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-[#4A90D9] hover:bg-[#357ABD] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4A90D9] transition duration-150">
                <span id="btn-text">重設密碼</span>
                <span id="btn-loading" class="hidden">
                    <i class="fas fa-spinner fa-spin mr-2"></i>處理中...
                </span>
            </button>
        </form>
        
        <div class="text-center text-sm text-[#6C757D]">
            <a href="<?= url('/account/login') ?>" class="hover:text-[#4A90D9]">
                <i class="fas fa-arrow-left mr-1"></i>返回登入
            </a>
        </div>
    </div>
</div>

<script>
    document.getElementById('reset-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('submit-btn');
        const btnText = document.getElementById('btn-text');
        const btnLoading = document.getElementById('btn-loading');
        const message = document.getElementById('message');
        
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        
        if (password !== confirmPassword) {
            message.textContent = '兩次密碼輸入不一致';
            message.classList.remove('hidden', 'bg-[#D4EDDA]', 'text-[#155724]');
            message.classList.add('bg-[#F8D7DA]', 'text-[#721C24]');
            return;
        }
        
        submitBtn.disabled = true;
        btnText.classList.add('hidden');
        btnLoading.classList.remove('hidden');
        message.classList.add('hidden');
        
        try {
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            
            const response = await fetch('<?= url('/account/do-reset-password') ?>', {
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
                setTimeout(() => {
                    window.location.href = result.redirect || '/account/login';
                }, 2000);
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
