<?php $currentPage = 'users'; ?>

<!-- Header -->
<div class="mb-6">
    <div class="flex items-center space-x-2 text-sm text-[#6C757D] mb-2">
        <a href="<?= url('/admin/users') ?>" class="hover:text-[#4A90D9]">使用者管理</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-[#212529]">編輯使用者</span>
    </div>
    <h2 class="text-2xl font-bold text-[#212529]">編輯使用者</h2>
</div>

<!-- Form -->
<div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm">
    <form id="user-form" class="p-6 space-y-6">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Username (Read-only) -->
            <div>
                <label for="username" class="block text-sm font-medium text-[#212529] mb-1">帳號</label>
                <input type="text" id="username" value="<?= htmlspecialchars($user['username']) ?>" disabled
                       class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg bg-[#F8F9FA] text-[#6C757D]">
                <p class="mt-1 text-xs text-[#6C757D]">帳號建立後無法修改</p>
            </div>
            
            <!-- Display Name -->
            <div>
                <label for="display_name" class="block text-sm font-medium text-[#212529] mb-1">顯示名稱</label>
                <input type="text" id="display_name" name="display_name" value="<?= htmlspecialchars($user['display_name'] ?? '') ?>"
                       class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                       placeholder="請輸入顯示名稱">
            </div>
            
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-[#212529] mb-1">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>"
                       class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                       placeholder="請輸入 Email">
            </div>
            
            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-[#212529] mb-1">新密碼</label>
                <div class="relative">
                    <input type="password" id="password" name="password"
                           class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                           placeholder="留空則不變更密碼">
                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-[#ADB5BD] hover:text-[#6C757D]" onclick="togglePassword('password')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <p class="mt-1 text-xs text-[#6C757D]">留空則不變更密碼</p>
            </div>
            
            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-[#212529] mb-1">狀態</label>
                <select id="status" name="status" class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                        <?= $user['id'] == $_SESSION['user']['id'] ? 'disabled' : '' ?>>
                    <option value="1" <?= $user['status'] == 1 ? 'selected' : '' ?>>啟用</option>
                    <option value="0" <?= $user['status'] == 0 ? 'selected' : '' ?>>停用</option>
                </select>
                <?php if ($user['id'] == $_SESSION['user']['id']): ?>
                <p class="mt-1 text-xs text-[#6C757D]">不能變更自己的狀態</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Roles -->
        <?php if ($user['id'] != $_SESSION['user']['id']): ?>
        <div>
            <label class="block text-sm font-medium text-[#212529] mb-2">角色</label>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <?php foreach ($roles ?? [] as $role): ?>
                <label class="flex items-center p-3 bg-[#F8F9FA] rounded-lg hover:bg-[#EBF4FB] cursor-pointer border border-[#E9ECEF]">
                    <input type="checkbox" name="roles[]" value="<?= $role['id'] ?>"
                           <?= in_array($role['id'], $userRoles ?? []) ? 'checked' : '' ?>
                           class="w-4 h-4 text-[#4A90D9] rounded focus:ring-[#4A90D9] border-[#DEE2E6]">
                    <span class="ml-2 text-sm text-[#212529]"><?= htmlspecialchars($role['role_name'] ?? '') ?></span>
                </label>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- User Info -->
        <div class="bg-[#F8F9FA] rounded-lg p-4 border border-[#E9ECEF]">
            <h4 class="font-medium text-[#212529] mb-2">使用者資訊</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <span class="text-[#6C757D]">建立時間：</span>
                    <span class="text-[#212529]"><?= $user['created_at'] ? date('Y-m-d H:i', strtotime($user['created_at'])) : '-' ?></span>
                </div>
                <div>
                    <span class="text-[#6C757D]">最後登入：</span>
                    <span class="text-[#212529]"><?= $user['last_login_at'] ? date('Y-m-d H:i', strtotime($user['last_login_at'])) : '-' ?></span>
                </div>
                <div>
                    <span class="text-[#6C757D]">登入 IP：</span>
                    <span class="text-[#212529]"><?= $user['last_login_ip'] ?? '-' ?></span>
                </div>
                <div>
                    <span class="text-[#6C757D]">密碼變更：</span>
                    <span class="text-[#212529]"><?= $user['password_changed_at'] ? date('Y-m-d H:i', strtotime($user['password_changed_at'])) : '-' ?></span>
                </div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="flex justify-end space-x-4 pt-6 border-t border-[#E9ECEF]">
            <a href="<?= url('/admin/users') ?>" class="px-6 py-2 bg-[#F8F9FA] text-[#212529] rounded-lg hover:bg-[#E9ECEF] border border-[#DEE2E6]">
                取消
            </a>
            <button type="submit" id="submit-btn" class="px-6 py-2 bg-[#4A90D9] text-white rounded-lg hover:bg-[#357ABD]">
                <span id="btn-text">儲存變更</span>
                <span id="btn-loading" class="hidden">
                    <i class="fas fa-spinner fa-spin mr-2"></i>處理中...
                </span>
            </button>
        </div>
        
        <div id="error-message" class="hidden p-4 bg-[#F8D7DA] text-[#721C24] rounded-lg"></div>
    </form>
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
    
    document.getElementById('user-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('submit-btn');
        const btnText = document.getElementById('btn-text');
        const btnLoading = document.getElementById('btn-loading');
        const errorMessage = document.getElementById('error-message');
        
        submitBtn.disabled = true;
        btnText.classList.add('hidden');
        btnLoading.classList.remove('hidden');
        errorMessage.classList.add('hidden');
        
        try {
            const formData = new FormData(this);
            const data = {
                csrf_token: formData.get('csrf_token'),
                display_name: formData.get('display_name'),
                email: formData.get('email'),
                password: formData.get('password'),
                status: formData.get('status') || '<?= $user['status'] ?>',
                roles: formData.getAll('roles[]')
            };
            
            const response = await fetch('<?= url('/admin/users/update/' . $user['id']) ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                showToast(result.message, 'success');
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
