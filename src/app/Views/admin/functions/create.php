<?php $currentPage = 'functions'; ?>

<!-- Header -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-[#212529]">新增功能</h2>
        <p class="text-[#6C757D]">建立新的系統功能</p>
    </div>
    <a href="<?= url('/admin/functions') ?>" class="bg-[#6C757D] text-white px-4 py-2 rounded-lg hover:bg-[#5a6268] transition">
        <i class="fas fa-arrow-left mr-2"></i>返回列表
    </a>
</div>

<!-- Form -->
<div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm p-6">
    <form id="create-function-form" class="space-y-6">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-[#212529] mb-2">功能名稱 <span class="text-[#DC3545]">*</span></label>
                <input type="text" name="name" required
                       class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                       placeholder="請輸入功能名稱">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-[#212529] mb-2">功能代碼 <span class="text-[#DC3545]">*</span></label>
                <input type="text" name="code" required
                       class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                       placeholder="例如：admin.users">
                <p class="text-xs text-[#6C757D] mt-1">只能包含小寫英文、數字、底線和點</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-[#212529] mb-2">父功能</label>
                <select name="parent_id"
                        class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]">
                    <option value="">無（頂層功能）</option>
                    <?php if (!empty($parentFunctions)): ?>
                    <?php foreach ($parentFunctions as $parent): ?>
                    <option value="<?= $parent['id'] ?>"><?= htmlspecialchars($parent['function_name'] ?? $parent['name'] ?? '') ?></option>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-[#212529] mb-2">URL</label>
                <input type="text" name="url"
                       class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                       placeholder="例如：/admin/users">
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-[#212529] mb-2">圖示</label>
                <input type="text" name="icon"
                       class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                       placeholder="例如：fas fa-users">
                <p class="text-xs text-[#6C757D] mt-1">使用 Font Awesome 圖示類別</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-[#212529] mb-2">排序</label>
                <input type="number" name="sort_order" value="0"
                       class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                       placeholder="0">
                <p class="text-xs text-[#6C757D] mt-1">數字越小排越前面</p>
            </div>
        </div>
        
        <div>
            <label class="flex items-center">
                <input type="checkbox" name="is_menu" value="1" checked
                       class="w-4 h-4 text-[#4A90D9] border-[#DEE2E6] rounded focus:ring-[#4A90D9]">
                <span class="ml-2 text-sm text-[#212529]">顯示於選單</span>
            </label>
        </div>
        
        <div class="flex justify-end space-x-4 pt-4 border-t border-[#E9ECEF]">
            <a href="<?= url('/admin/functions') ?>" class="px-6 py-2 bg-[#F8F9FA] text-[#212529] rounded-lg hover:bg-[#E9ECEF] border border-[#DEE2E6]">
                取消
            </a>
            <button type="submit" class="px-6 py-2 bg-[#4A90D9] text-white rounded-lg hover:bg-[#357ABD]">
                <i class="fas fa-save mr-2"></i>儲存
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('create-function-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        data.is_menu = formData.has('is_menu') ? 1 : 0;
        
        try {
            const response = await fetch('<?= url('/admin/functions/store') ?>', {
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
