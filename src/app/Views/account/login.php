<div class="min-h-screen flex items-center justify-center bg-[#F8F9FA] py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-xl border border-[#E9ECEF] shadow-sm">
        <div class="text-center">
            <h1 class="text-3xl font-bold text-[#4A90D9]">鴻展盟</h1>
            <p class="mt-2 text-sm text-[#6C757D]">管理系統登入</p>
        </div>
        
        <form id="login-form" class="mt-8 space-y-6">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            
            <div>
                <label for="username" class="block text-sm font-medium text-[#212529]">帳號</label>
                <div class="mt-1 relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-[#ADB5BD]">
                        <i class="fas fa-user"></i>
                    </span>
                    <input type="text" id="username" name="username" required
                           class="pl-10 w-full px-4 py-3 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                           placeholder="請輸入帳號">
                </div>
            </div>
            
            <div>
                <label for="password" class="block text-sm font-medium text-[#212529]">密碼</label>
                <div class="mt-1 relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-[#ADB5BD]">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" id="password" name="password" required
                           class="pl-10 w-full px-4 py-3 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                           placeholder="請輸入密碼">
                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-[#ADB5BD] hover:text-[#6C757D]" onclick="togglePassword()">
                        <i class="fas fa-eye" id="password-toggle-icon"></i>
                    </button>
                </div>
            </div>
            
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember" 
                           class="h-4 w-4 text-[#4A90D9] focus:ring-[#4A90D9] border-[#DEE2E6] rounded">
                    <label for="remember" class="ml-2 block text-sm text-[#212529]">記住我</label>
                </div>
                <a href="<?= url('/account/forgot-password') ?>" class="text-sm text-[#4A90D9] hover:text-[#357ABD]">
                    忘記密碼？
                </a>
            </div>
            
            <div id="error-message" class="hidden p-3 bg-[#F8D7DA] text-[#721C24] rounded-lg text-sm"></div>
            
            <button type="submit" id="submit-btn"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-[#4A90D9] hover:bg-[#357ABD] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4A90D9] transition duration-150">
                <span id="btn-text">登入</span>
                <span id="btn-loading" class="hidden">
                    <i class="fas fa-spinner fa-spin mr-2"></i>登入中...
                </span>
            </button>
        </form>
        
        <div class="text-center text-sm text-[#6C757D]">
            <a href="<?= url('/') ?>" class="hover:text-[#4A90D9]">
                <i class="fas fa-arrow-left mr-1"></i>返回首頁
            </a>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const icon = document.getElementById('password-toggle-icon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
    
    document.getElementById('login-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('submit-btn');
        const btnText = document.getElementById('btn-text');
        const btnLoading = document.getElementById('btn-loading');
        const errorMessage = document.getElementById('error-message');
        
        // Disable button
        submitBtn.disabled = true;
        btnText.classList.add('hidden');
        btnLoading.classList.remove('hidden');
        errorMessage.classList.add('hidden');
        
        try {
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            
            const response = await fetch('<?= url('/account/do-login') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                window.location.href = result.redirect || '/admin';
            } else {
                errorMessage.textContent = result.message;
                errorMessage.classList.remove('hidden');
            }
        } catch (error) {
            errorMessage.textContent = '發生錯誤，請稍後再試';
            errorMessage.classList.remove('hidden');
        } finally {
            submitBtn.disabled = false;
            btnText.classList.remove('hidden');
            btnLoading.classList.add('hidden');
        }
    });
</script>
