<?php $currentPage = 'action-logs'; ?>

<!-- Header -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-[#212529]">操作紀錄</h2>
        <p class="text-[#6C757D]">檢視系統操作紀錄</p>
    </div>
</div>

<!-- Search & Filter -->
<div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm mb-6 p-4">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div>
            <label class="block text-xs text-[#6C757D] mb-1">使用者</label>
            <select name="user_id" class="w-full px-3 py-2 border border-[#DEE2E6] rounded-lg text-sm text-[#212529]">
                <option value="">全部使用者</option>
                <?php foreach ($users ?? [] as $user): ?>
                <option value="<?= $user['id'] ?>" <?= ($filters['user_id'] ?? '') == $user['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($user['display_name'] ?? $user['username']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-xs text-[#6C757D] mb-1">控制器</label>
            <input type="text" name="controller" value="<?= htmlspecialchars($filters['controller'] ?? '') ?>"
                   class="w-full px-3 py-2 border border-[#DEE2E6] rounded-lg text-sm text-[#212529]"
                   placeholder="Controller">
        </div>
        <div>
            <label class="block text-xs text-[#6C757D] mb-1">方法</label>
            <input type="text" name="action" value="<?= htmlspecialchars($filters['action'] ?? '') ?>"
                   class="w-full px-3 py-2 border border-[#DEE2E6] rounded-lg text-sm text-[#212529]"
                   placeholder="Action">
        </div>
        <div>
            <label class="block text-xs text-[#6C757D] mb-1">開始日期</label>
            <input type="date" name="date_from" value="<?= htmlspecialchars($filters['date_from'] ?? '') ?>"
                   class="w-full px-3 py-2 border border-[#DEE2E6] rounded-lg text-sm text-[#212529]">
        </div>
        <div>
            <label class="block text-xs text-[#6C757D] mb-1">結束日期</label>
            <input type="date" name="date_to" value="<?= htmlspecialchars($filters['date_to'] ?? '') ?>"
                   class="w-full px-3 py-2 border border-[#DEE2E6] rounded-lg text-sm text-[#212529]">
        </div>
        <div class="flex items-end space-x-2">
            <button type="submit" class="flex-1 bg-[#4A90D9] text-white px-4 py-2 rounded-lg hover:bg-[#357ABD] text-sm">
                <i class="fas fa-search mr-1"></i>搜尋
            </button>
            <a href="<?= url('/admin/action-logs') ?>" class="px-4 py-2 bg-[#F8F9FA] text-[#212529] rounded-lg hover:bg-[#E9ECEF] border border-[#DEE2E6] text-sm">
                <i class="fas fa-redo"></i>
            </a>
        </div>
    </form>
</div>

<!-- Logs Table -->
<div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-[#E9ECEF]">
        <thead class="bg-[#F8F9FA]">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-[#212529] uppercase">時間</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-[#212529] uppercase">使用者</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-[#212529] uppercase">控制器 / 方法</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-[#212529] uppercase">IP 位址</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-[#212529] uppercase">操作</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-[#E9ECEF]">
            <?php if (empty($logs)): ?>
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-[#6C757D]">
                    <i class="fas fa-clipboard-list text-4xl mb-2"></i>
                    <p>沒有找到操作紀錄</p>
                </td>
            </tr>
            <?php else: ?>
            <?php foreach ($logs as $log): ?>
            <tr class="hover:bg-[#F5F5F5]">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-[#6C757D]">
                    <?= date('Y-m-d H:i:s', strtotime($log['created_at'])) ?>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-[#212529]"><?= htmlspecialchars($log['display_name'] ?? $log['username'] ?? 'N/A') ?></div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <code class="text-sm bg-[#F8F9FA] px-2 py-1 rounded text-[#4A90D9]">
                        <?= htmlspecialchars($log['controller'] ?? '') ?>/<?= htmlspecialchars($log['action'] ?? '') ?>
                    </code>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-[#6C757D]">
                    <?= htmlspecialchars($log['ip_address'] ?? '') ?>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <a href="<?= url('/admin/action-logs/show/' . $log['id']) ?>" class="text-[#4A90D9] hover:text-[#357ABD]">
                        <i class="fas fa-eye"></i> 詳情
                    </a>
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
        <a href="?page=<?= $pagination['current_page'] - 1 ?>&<?= http_build_query($filters) ?>" 
           class="px-3 py-2 bg-white border border-[#DEE2E6] rounded-lg hover:bg-[#F8F9FA] text-[#212529]">
            <i class="fas fa-chevron-left"></i>
        </a>
        <?php endif; ?>
        
        <?php for ($i = max(1, $pagination['current_page'] - 2); $i <= min($pagination['total_pages'], $pagination['current_page'] + 2); $i++): ?>
        <a href="?page=<?= $i ?>&<?= http_build_query($filters) ?>" 
           class="px-3 py-2 border rounded-lg <?= $i == $pagination['current_page'] ? 'bg-[#4A90D9] text-white border-[#4A90D9]' : 'bg-white border-[#DEE2E6] hover:bg-[#F8F9FA] text-[#212529]' ?>">
            <?= $i ?>
        </a>
        <?php endfor; ?>
        
        <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
        <a href="?page=<?= $pagination['current_page'] + 1 ?>&<?= http_build_query($filters) ?>" 
           class="px-3 py-2 bg-white border border-[#DEE2E6] rounded-lg hover:bg-[#F8F9FA] text-[#212529]">
            <i class="fas fa-chevron-right"></i>
        </a>
        <?php endif; ?>
    </nav>
</div>
<?php endif; ?>
