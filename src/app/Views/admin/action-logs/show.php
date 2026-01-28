<?php $currentPage = 'action-logs'; ?>

<!-- Header -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-[#212529]">操作紀錄詳情</h2>
        <p class="text-[#6C757D]">檢視操作紀錄詳細資訊</p>
    </div>
    <a href="<?= url('/admin/action-logs') ?>" class="bg-[#6C757D] text-white px-4 py-2 rounded-lg hover:bg-[#5a6268] transition">
        <i class="fas fa-arrow-left mr-2"></i>返回列表
    </a>
</div>

<!-- Log Details -->
<div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-[#6C757D] mb-1">紀錄 ID</label>
            <p class="text-[#212529]"><?= $log['id'] ?? '-' ?></p>
        </div>
        <div>
            <label class="block text-sm font-medium text-[#6C757D] mb-1">時間</label>
            <p class="text-[#212529]"><?= $log['created_at'] ?? '-' ?></p>
        </div>
        <div>
            <label class="block text-sm font-medium text-[#6C757D] mb-1">使用者</label>
            <p class="text-[#212529]"><?= htmlspecialchars($log['display_name'] ?? $log['username'] ?? 'N/A') ?></p>
        </div>
        <div>
            <label class="block text-sm font-medium text-[#6C757D] mb-1">IP 位址</label>
            <p class="text-[#212529]"><?= htmlspecialchars($log['ip_address'] ?? '-') ?></p>
        </div>
        <div>
            <label class="block text-sm font-medium text-[#6C757D] mb-1">控制器</label>
            <code class="bg-[#F8F9FA] px-2 py-1 rounded text-[#4A90D9]"><?= htmlspecialchars($log['controller'] ?? '-') ?></code>
        </div>
        <div>
            <label class="block text-sm font-medium text-[#6C757D] mb-1">方法</label>
            <code class="bg-[#F8F9FA] px-2 py-1 rounded text-[#4A90D9]"><?= htmlspecialchars($log['action'] ?? '-') ?></code>
        </div>
    </div>
    
    <hr class="my-6 border-[#E9ECEF]">
    
    <div>
        <label class="block text-sm font-medium text-[#6C757D] mb-2">User Agent</label>
        <p class="text-sm text-[#212529] bg-[#F8F9FA] p-3 rounded-lg break-all"><?= htmlspecialchars($log['user_agent'] ?? '-') ?></p>
    </div>
    
    <?php if (!empty($log['request_data'])): ?>
    <div class="mt-6">
        <label class="block text-sm font-medium text-[#6C757D] mb-2">請求資料</label>
        <pre class="text-sm text-[#212529] bg-[#F8F9FA] p-3 rounded-lg overflow-x-auto"><?= htmlspecialchars(json_encode($log['request_data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) ?></pre>
    </div>
    <?php endif; ?>
</div>
