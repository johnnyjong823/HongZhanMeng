<?php $currentPage = 'functions'; ?>

<!-- Header -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-[#212529]">功能管理</h2>
        <p class="text-[#6C757D]">管理系統功能選單</p>
    </div>
    <a href="<?= url('/admin/functions/create') ?>" class="bg-[#4A90D9] text-white px-4 py-2 rounded-lg hover:bg-[#357ABD] transition">
        <i class="fas fa-plus mr-2"></i>新增功能
    </a>
</div>

<!-- Functions Tree -->
<div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-[#E9ECEF]">
        <thead class="bg-[#F8F9FA]">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-[#212529] uppercase">功能名稱</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-[#212529] uppercase">代碼</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-[#212529] uppercase">URL</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-[#212529] uppercase">排序</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-[#212529] uppercase">狀態</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-[#212529] uppercase">操作</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-[#E9ECEF]">
            <?php if (empty($functions)): ?>
            <tr>
                <td colspan="6" class="px-6 py-8 text-center text-[#6C757D]">
                    <i class="fas fa-sitemap text-4xl mb-2"></i>
                    <p>沒有找到功能</p>
                </td>
            </tr>
            <?php else: ?>
            <?php 
            function renderFunctionRow($func, $level = 0) {
                $padding = $level * 24;
            ?>
            <tr class="hover:bg-[#F5F5F5]">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center" style="padding-left: <?= $padding ?>px;">
                        <?php if (!empty($func['icon'])): ?>
                        <i class="<?= htmlspecialchars($func['icon']) ?> text-[#4A90D9] mr-3"></i>
                        <?php else: ?>
                        <i class="fas fa-cube text-[#6C757D] mr-3"></i>
                        <?php endif; ?>
                        <span class="text-sm font-medium text-[#212529]"><?= htmlspecialchars($func['function_name'] ?? $func['name'] ?? '') ?></span>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <code class="text-sm bg-[#F8F9FA] px-2 py-1 rounded text-[#6C757D]"><?= htmlspecialchars($func['function_code'] ?? $func['code'] ?? '') ?></code>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-[#6C757D]">
                    <?= htmlspecialchars($func['url'] ?? '-') ?>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-[#6C757D]">
                    <?= $func['sort_order'] ?? 0 ?>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <?php if (($func['status'] ?? 0) == 1): ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#D4EDDA] text-[#155724]">
                        <i class="fas fa-check-circle mr-1"></i>啟用
                    </span>
                    <?php else: ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#F8D7DA] text-[#721C24]">
                        <i class="fas fa-times-circle mr-1"></i>停用
                    </span>
                    <?php endif; ?>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <a href="<?= url('/admin/functions/edit/' . $func['id']) ?>" class="text-[#4A90D9] hover:text-[#357ABD] mr-3">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button onclick="deleteFunction(<?= $func['id'] ?>, '<?= htmlspecialchars($func['function_name'] ?? $func['name'] ?? '') ?>')" class="text-[#DC3545] hover:text-[#a71d2a]">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
            <?php 
                if (!empty($func['children'])) {
                    foreach ($func['children'] as $child) {
                        renderFunctionRow($child, $level + 1);
                    }
                }
            }
            
            foreach ($functions as $func) {
                renderFunctionRow($func);
            }
            ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Delete Modal -->
<div id="delete-modal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black bg-opacity-50" onclick="closeDeleteModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 border border-[#E9ECEF]">
            <div class="text-center">
                <div class="text-[#DC3545] text-5xl mb-4">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3 class="text-lg font-semibold text-[#212529] mb-2">確定要刪除嗎？</h3>
                <p class="text-[#6C757D] mb-6">您確定要刪除功能 <span id="delete-funcname" class="font-semibold text-[#212529]"></span> 嗎？此操作無法復原。</p>
                <div class="flex space-x-4">
                    <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2 bg-[#F8F9FA] text-[#212529] rounded-lg hover:bg-[#E9ECEF] border border-[#DEE2E6]">
                        取消
                    </button>
                    <button onclick="confirmDelete()" class="flex-1 px-4 py-2 bg-[#DC3545] text-white rounded-lg hover:bg-[#a71d2a]">
                        確定刪除
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let deleteFunctionId = null;
    
    function deleteFunction(id, name) {
        deleteFunctionId = id;
        document.getElementById('delete-funcname').textContent = name;
        document.getElementById('delete-modal').classList.remove('hidden');
    }
    
    function closeDeleteModal() {
        document.getElementById('delete-modal').classList.add('hidden');
        deleteFunctionId = null;
    }
    
    async function confirmDelete() {
        if (!deleteFunctionId) return;
        
        try {
            const response = await fetch(`<?= url('/admin/functions/delete/') ?>${deleteFunctionId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': getCsrfToken()
                },
                body: JSON.stringify({ csrf_token: getCsrfToken() })
            });
            
            const result = await response.json();
            
            if (result.success) {
                showToast(result.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(result.message, 'error');
            }
        } catch (error) {
            showToast('發生錯誤，請稍後再試', 'error');
        }
        
        closeDeleteModal();
    }
</script>
