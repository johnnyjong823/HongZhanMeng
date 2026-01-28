<?php $currentPage = 'dashboard'; ?>

<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center space-x-2 text-sm text-[#6C757D] mb-2">
            <a href="<?= url('/admin') ?>" class="hover:text-[#4A90D9]">儀表板</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-[#212529]">修改密碼</span>
        </div>
        <h2 class="text-2xl font-bold text-[#212529]">修改密碼</h2>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm">
        <form id="password-form" class="p-6 space-y-6">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            
            <div>
                <label for="current_password" class="block text-sm font-medium text-[#212529] mb-1">
                    目前密碼 <span class="text-[#DC3545]">*</span>
                </label>
                <div class="relative">
                    <input type="password" id="current_password" name="current_password" required
                           class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                           placeholder="請輸入目前密碼">
                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-[#ADB5BD] hover:text-[#6C757D]" onclick="togglePassword('current_password')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            
            <div>
                <label for="new_password" class="block text-sm font-medium text-[#212529] mb-1">
                    新密碼 <span class="text-[#DC3545]">*</span>
                </label>
                <div class="relative">
                    <input type="password" id="new_password" name="new_password" required
                           class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                           placeholder="請輸入新密碼">
                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-[#ADB5BD] hover:text-[#6C757D]" onclick="togglePassword('new_password')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <p class="mt-1 text-xs text-[#6C757D]">至少 8 個字元，須包含英文和數字</p>
            </div>
            
            <div>
                <label for="confirm_password" class="block text-sm font-medium text-[#212529] mb-1">
                    確認新密碼 <span class="text-[#DC3545]">*</span>
                </label>
                <div class="relative">
                    <input type="password" id="confirm_password" name="confirm_password" required
                           class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                           placeholder="請再次輸入新密碼">
                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-[#ADB5BD] hover:text-[#6C757D]" onclick="togglePassword('confirm_password')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            
            <div id="message" class="hidden p-4 rounded-lg"></div>
            
            <div class="flex justify-end space-x-4 pt-4 border-t border-[#E9ECEF]">
                <a href="<?= url('/admin') ?>" class="px-6 py-2 bg-[#F8F9FA] text-[#212529] rounded-lg hover:bg-[#E9ECEF] border border-[#DEE2E6]">
                    取消
                </a>
                <button type="submit" id="submit-btn" class="px-6 py-2 bg-[#4A90D9] text-white rounded-lg hover:bg-[#357ABD]">
                    <span id="btn-text">變更密碼</span>
                    <span id="btn-loading" class="hidden">
                        <i class="fas fa-spinner fa-spin mr-2"></i>處理中...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = field.nextElementSibling.querySelector('i');
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
    
    document.getElementById('password-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('submit-btn');
        const btnText = document.getElementById('btn-text');
        const btnLoading = document.getElementById('btn-loading');
        const message = document.getElementById('message');
        
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        
        if (newPassword !== confirmPassword) {
            message.textContent = '新密碼與確認密碼不一致';
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
            
            const response = await fetch('<?= url('/admin/account/do-change-password') ?>', {
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
                this.reset();
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
