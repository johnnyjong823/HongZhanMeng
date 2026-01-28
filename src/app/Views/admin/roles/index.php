<?php $currentPage = 'roles'; ?>

<!-- Header -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-[#212529]">角色管理</h2>
        <p class="text-[#6C757D]">管理系統角色與權限</p>
    </div>
    <a href="<?= url('/admin/roles/create') ?>" class="bg-[#4A90D9] text-white px-4 py-2 rounded-lg hover:bg-[#357ABD] transition">
        <i class="fas fa-plus mr-2"></i>新增角色
    </a>
</div>

<!-- Roles Table -->
<div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-[#E9ECEF]">
        <thead class="bg-[#F8F9FA]">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-[#212529] uppercase">角色名稱</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-[#212529] uppercase">說明</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-[#212529] uppercase">權限等級</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-[#212529] uppercase">狀態</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-[#212529] uppercase">操作</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-[#E9ECEF]">
            <?php if (empty($roles)): ?>
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-[#6C757D]">
                    <i class="fas fa-user-shield text-4xl mb-2"></i>
                    <p>沒有找到角色</p>
                </td>
            </tr>
            <?php else: ?>
            <?php foreach ($roles as $role): ?>
            <tr class="hover:bg-[#F5F5F5]">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-[#4A90D9] rounded-full flex items-center justify-center text-white">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-[#212529]"><?= htmlspecialchars($role['role_name'] ?? $role['name'] ?? '') ?></div>
                            <div class="text-sm text-[#6C757D]"><?= htmlspecialchars($role['role_code'] ?? '') ?></div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-[#6C757D]">
                    <?= htmlspecialchars($role['description'] ?? '-') ?>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#EBF4FB] text-[#4A90D9]">
                        Level <?= $role['level'] ?? '-' ?>
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <?php if (($role['status'] ?? 0) == 1): ?>
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
                    <a href="<?= url('/admin/roles/edit/' . $role['id']) ?>" class="text-[#4A90D9] hover:text-[#357ABD] mr-3">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button onclick="deleteRole(<?= $role['id'] ?>, '<?= htmlspecialchars($role['role_name'] ?? $role['name'] ?? '') ?>')" class="text-[#DC3545] hover:text-[#a71d2a]">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
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
                <p class="text-[#6C757D] mb-6">您確定要刪除角色 <span id="delete-rolename" class="font-semibold text-[#212529]"></span> 嗎？此操作無法復原。</p>
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
    let deleteRoleId = null;
    
    function deleteRole(id, name) {
        deleteRoleId = id;
        document.getElementById('delete-rolename').textContent = name;
        document.getElementById('delete-modal').classList.remove('hidden');
    }
    
    function closeDeleteModal() {
        document.getElementById('delete-modal').classList.add('hidden');
        deleteRoleId = null;
    }
    
    async function confirmDelete() {
        if (!deleteRoleId) return;
        
        try {
            const response = await fetch(`<?= url('/admin/roles/delete/') ?>${deleteRoleId}`, {
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
