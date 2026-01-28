<?php $currentPage = 'roles'; ?>

<!-- Header -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-[#212529]">新增角色</h2>
        <p class="text-[#6C757D]">建立新的系統角色</p>
    </div>
    <a href="<?= url('/admin/roles') ?>" class="bg-[#6C757D] text-white px-4 py-2 rounded-lg hover:bg-[#5a6268] transition">
        <i class="fas fa-arrow-left mr-2"></i>返回列表
    </a>
</div>

<!-- Form -->
<div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm p-6">
    <form id="create-role-form" class="space-y-6">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-[#212529] mb-2">角色名稱 <span class="text-[#DC3545]">*</span></label>
                <input type="text" name="name" required
                       class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                       placeholder="請輸入角色名稱">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-[#212529] mb-2">權限等級 <span class="text-[#DC3545]">*</span></label>
                <select name="level" required
                        class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]">
                    <?php foreach ($levels as $value => $label): ?>
                    <option value="<?= $value ?>"><?= htmlspecialchars($label) ?></option>
                    <?php endforeach; ?>
                </select>
                <p class="text-xs text-[#6C757D] mt-1">數字越小權限越高</p>
            </div>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-[#212529] mb-2">說明</label>
            <textarea name="description" rows="3"
                      class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                      placeholder="角色說明（選填）"></textarea>
        </div>
        
        <!-- Functions Permission -->
        <div>
            <label class="block text-sm font-medium text-[#212529] mb-2">功能權限</label>
            <div class="border border-[#DEE2E6] rounded-lg p-4 max-h-96 overflow-y-auto">
                <?php if (empty($functions)): ?>
                <p class="text-[#6C757D] text-center py-4">尚無可設定的功能</p>
                <?php else: ?>
                <div class="space-y-3">
                    <?php foreach ($functions as $function): ?>
                    <div class="flex items-center">
                        <input type="checkbox" name="functions[]" value="<?= $function['id'] ?>" 
                               id="func_<?= $function['id'] ?>"
                               class="w-4 h-4 text-[#4A90D9] border-[#DEE2E6] rounded focus:ring-[#4A90D9]">
                        <label for="func_<?= $function['id'] ?>" class="ml-2 text-sm text-[#212529]">
                            <?= htmlspecialchars($function['function_name']) ?>
                        </label>
                    </div>
                    <?php if (!empty($function['children'])): ?>
                    <div class="ml-6 space-y-2">
                        <?php foreach ($function['children'] as $child): ?>
                        <div class="flex items-center">
                            <input type="checkbox" name="functions[]" value="<?= $child['id'] ?>" 
                                   id="func_<?= $child['id'] ?>"
                                   class="w-4 h-4 text-[#4A90D9] border-[#DEE2E6] rounded focus:ring-[#4A90D9]">
                            <label for="func_<?= $child['id'] ?>" class="ml-2 text-sm text-[#6C757D]">
                                <?= htmlspecialchars($child['function_name']) ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="flex justify-end space-x-4 pt-4 border-t border-[#E9ECEF]">
            <a href="<?= url('/admin/roles') ?>" class="px-6 py-2 bg-[#F8F9FA] text-[#212529] rounded-lg hover:bg-[#E9ECEF] border border-[#DEE2E6]">
                取消
            </a>
            <button type="submit" class="px-6 py-2 bg-[#4A90D9] text-white rounded-lg hover:bg-[#357ABD]">
                <i class="fas fa-save mr-2"></i>儲存
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('create-role-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = {};
        
        formData.forEach((value, key) => {
            if (key === 'functions[]') {
                if (!data.functions) data.functions = [];
                data.functions.push(value);
            } else {
                data[key] = value;
            }
        });
        
        try {
            const response = await fetch('<?= url('/admin/roles/store') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': getCsrfToken()
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                showToast(result.message, 'success');
                if (result.redirect) {
                    setTimeout(() => window.location.href = result.redirect, 1000);
                }
            } else {
                showToast(result.message, 'error');
            }
        } catch (error) {
            showToast('發生錯誤，請稍後再試', 'error');
        }
    });
</script>
