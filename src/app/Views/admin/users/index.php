<?php $currentPage = 'users'; ?>

<!-- Header -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-[#212529]">使用者管理</h2>
        <p class="text-[#6C757D]">管理系統使用者帳號</p>
    </div>
    <a href="<?= url('/admin/users/create') ?>" class="bg-[#4A90D9] text-white px-4 py-2 rounded-lg hover:bg-[#357ABD] transition">
        <i class="fas fa-plus mr-2"></i>新增使用者
    </a>
</div>

<!-- Search & Filter -->
<div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm mb-6 p-4">
    <form method="GET" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="search" value="<?= htmlspecialchars($search ?? '') ?>" 
                   placeholder="搜尋帳號、名稱或 Email..."
                   class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]">
        </div>
        <div class="w-40">
            <select name="status" class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]">
                <option value="">全部狀態</option>
                <option value="1" <?= ($status ?? '') === '1' ? 'selected' : '' ?>>啟用</option>
                <option value="0" <?= ($status ?? '') === '0' ? 'selected' : '' ?>>停用</option>
            </select>
        </div>
        <button type="submit" class="bg-[#6C757D] text-white px-4 py-2 rounded-lg hover:bg-[#5a6268]">
            <i class="fas fa-search mr-2"></i>搜尋
        </button>
        <a href="<?= url('/admin/users') ?>" class="bg-[#F8F9FA] text-[#212529] px-4 py-2 rounded-lg hover:bg-[#E9ECEF] border border-[#DEE2E6]">
            <i class="fas fa-redo mr-2"></i>重設
        </a>
    </form>
</div>

<!-- Users Table -->
<div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-[#E9ECEF]">
        <thead class="bg-[#F8F9FA]">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-[#212529] uppercase">使用者</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-[#212529] uppercase">Email</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-[#212529] uppercase">角色</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-[#212529] uppercase">狀態</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-[#212529] uppercase">最後登入</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-[#212529] uppercase">操作</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-[#E9ECEF]">
            <?php if (empty($users)): ?>
            <tr>
                <td colspan="6" class="px-6 py-8 text-center text-[#6C757D]">
                    <i class="fas fa-users text-4xl mb-2"></i>
                    <p>沒有找到使用者</p>
                </td>
            </tr>
            <?php else: ?>
            <?php foreach ($users as $user): ?>
            <tr class="hover:bg-[#F5F5F5]">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-[#4A90D9] rounded-full flex items-center justify-center text-white font-semibold">
                            <?php
                            $name = $user['display_name'] ?? $user['username'];
                            echo htmlspecialchars(function_exists('mb_substr') ? mb_substr($name, 0, 1) : substr($name, 0, 1));
                            ?>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-[#212529]"><?= htmlspecialchars($user['display_name'] ?? $user['username']) ?></div>
                            <div class="text-sm text-[#6C757D]"><?= htmlspecialchars($user['username']) ?></div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-[#6C757D]">
                    <?= htmlspecialchars($user['email'] ?? '-') ?>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <?php 
                    $userRoles = $user['roles'] ?? [];
                    if (!empty($userRoles)):
                        foreach ($userRoles as $role): 
                    ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#EBF4FB] text-[#4A90D9] mr-1">
                        <?= htmlspecialchars($role['role_name'] ?? '') ?>
                    </span>
                    <?php 
                        endforeach;
                    else: 
                    ?>
                    <span class="text-[#ADB5BD]">-</span>
                    <?php endif; ?>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <?php if ($user['status'] == 1): ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#D4EDDA] text-[#155724]">
                        <i class="fas fa-check-circle mr-1"></i>啟用
                    </span>
                    <?php else: ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#F8D7DA] text-[#721C24]">
                        <i class="fas fa-times-circle mr-1"></i>停用
                    </span>
                    <?php endif; ?>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-[#6C757D]">
                    <?= $user['last_login_at'] ? date('Y-m-d H:i', strtotime($user['last_login_at'])) : '-' ?>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <a href="<?= url('/admin/users/edit/' . $user['id']) ?>" class="text-[#4A90D9] hover:text-[#357ABD] mr-3">
                        <i class="fas fa-edit"></i>
                    </a>
                    <?php if ($user['id'] != $_SESSION['user']['id']): ?>
                    <button onclick="deleteUser(<?= $user['id'] ?>, '<?= htmlspecialchars($user['username']) ?>')" class="text-[#DC3545] hover:text-[#a71d2a]">
                        <i class="fas fa-trash"></i>
                    </button>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Pagination -->
<?php if (!empty($pagination) && $pagination['total_pages'] > 1): ?>
<div class="mt-6 flex justify-center">
    <nav class="flex space-x-2">
        <?php if ($pagination['current_page'] > 1): ?>
        <a href="?page=<?= $pagination['current_page'] - 1 ?>&search=<?= urlencode($search ?? '') ?>&status=<?= urlencode($status ?? '') ?>" 
           class="px-3 py-2 bg-white border border-[#DEE2E6] rounded-lg hover:bg-[#F8F9FA] text-[#212529]">
            <i class="fas fa-chevron-left"></i>
        </a>
        <?php endif; ?>
        
        <?php for ($i = max(1, $pagination['current_page'] - 2); $i <= min($pagination['total_pages'], $pagination['current_page'] + 2); $i++): ?>
        <a href="?page=<?= $i ?>&search=<?= urlencode($search ?? '') ?>&status=<?= urlencode($status ?? '') ?>" 
           class="px-3 py-2 border rounded-lg <?= $i == $pagination['current_page'] ? 'bg-[#4A90D9] text-white border-[#4A90D9]' : 'bg-white border-[#DEE2E6] hover:bg-[#F8F9FA] text-[#212529]' ?>">
            <?= $i ?>
        </a>
        <?php endfor; ?>
        
        <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
        <a href="?page=<?= $pagination['current_page'] + 1 ?>&search=<?= urlencode($search ?? '') ?>&status=<?= urlencode($status ?? '') ?>" 
           class="px-3 py-2 bg-white border border-[#DEE2E6] rounded-lg hover:bg-[#F8F9FA] text-[#212529]">
            <i class="fas fa-chevron-right"></i>
        </a>
        <?php endif; ?>
    </nav>
</div>
<?php endif; ?>

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
                <p class="text-[#6C757D] mb-6">您確定要刪除使用者 <span id="delete-username" class="font-semibold text-[#212529]"></span> 嗎？此操作無法復原。</p>
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
    let deleteUserId = null;
    
    function deleteUser(id, username) {
        deleteUserId = id;
        document.getElementById('delete-username').textContent = username;
        document.getElementById('delete-modal').classList.remove('hidden');
    }
    
    function closeDeleteModal() {
        document.getElementById('delete-modal').classList.add('hidden');
        deleteUserId = null;
    }
    
    async function confirmDelete() {
        if (!deleteUserId) return;
        
        try {
            const response = await fetch(`<?= url('/admin/users/delete/') ?>${deleteUserId}`, {
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
