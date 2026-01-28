<?php $currentPage = 'login-logs'; ?>

<!-- Header -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-[#212529]">登入統計</h2>
        <p class="text-[#6C757D]">最近 <?= $days ?> 天的登入統計</p>
    </div>
    <a href="<?= url('/admin/login-logs') ?>" class="bg-[#6C757D] text-white px-4 py-2 rounded-lg hover:bg-[#5a6268] transition">
        <i class="fas fa-arrow-left mr-2"></i>返回列表
    </a>
</div>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm p-6">
        <div class="text-center">
            <p class="text-3xl font-bold text-[#4A90D9]"><?= number_format($stats['total'] ?? 0) ?></p>
            <p class="text-[#6C757D]">總登入次數</p>
        </div>
    </div>
    <div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm p-6">
        <div class="text-center">
            <p class="text-3xl font-bold text-[#155724]"><?= number_format($stats['success_count'] ?? 0) ?></p>
            <p class="text-[#6C757D]">成功登入</p>
        </div>
    </div>
    <div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm p-6">
        <div class="text-center">
            <p class="text-3xl font-bold text-[#DC3545]"><?= number_format($stats['failed_count'] ?? 0) ?></p>
            <p class="text-[#6C757D]">失敗嘗試</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Top Users -->
    <div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm">
        <div class="px-6 py-4 border-b border-[#E9ECEF]">
            <h3 class="text-lg font-semibold text-[#212529]">登入最多的使用者</h3>
        </div>
        <div class="p-6">
            <?php if (empty($topUsers)): ?>
            <p class="text-[#6C757D] text-center py-4">暫無資料</p>
            <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($topUsers as $index => $user): ?>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <span class="w-6 h-6 rounded-full bg-[#4A90D9] text-white text-xs flex items-center justify-center mr-3">
                            <?= $index + 1 ?>
                        </span>
                        <span class="text-[#212529]"><?= htmlspecialchars($user['username']) ?></span>
                    </div>
                    <span class="text-[#6C757D]"><?= number_format($user['login_count']) ?> 次</span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Failed Attempts -->
    <div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm">
        <div class="px-6 py-4 border-b border-[#E9ECEF]">
            <h3 class="text-lg font-semibold text-[#212529]">最近失敗嘗試</h3>
        </div>
        <div class="p-6">
            <?php if (empty($failedAttempts)): ?>
            <p class="text-[#6C757D] text-center py-4">暫無失敗紀錄</p>
            <?php else: ?>
            <div class="space-y-3 max-h-80 overflow-y-auto">
                <?php foreach ($failedAttempts as $attempt): ?>
                <div class="flex items-start justify-between text-sm border-b border-[#E9ECEF] pb-2">
                    <div>
                        <p class="text-[#212529] font-medium"><?= htmlspecialchars($attempt['username']) ?></p>
                        <p class="text-xs text-[#6C757D]"><?= htmlspecialchars($attempt['ip_address']) ?></p>
                    </div>
                    <div class="text-right">
                        <p class="text-[#DC3545] text-xs"><?= htmlspecialchars($attempt['failure_reason'] ?? '未知') ?></p>
                        <p class="text-xs text-[#6C757D]"><?= date('m/d H:i', strtotime($attempt['login_at'])) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Daily Stats -->
<div class="mt-6 bg-white rounded-lg border border-[#E9ECEF] shadow-sm">
    <div class="px-6 py-4 border-b border-[#E9ECEF]">
        <h3 class="text-lg font-semibold text-[#212529]">每日登入統計</h3>
    </div>
    <div class="p-6">
        <?php if (empty($dailyStats)): ?>
        <p class="text-[#6C757D] text-center py-4">暫無資料</p>
        <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#E9ECEF]">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-[#212529]">日期</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-[#155724]">成功</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-[#DC3545]">失敗</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-[#212529]">成功率</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E9ECEF]">
                    <?php foreach ($dailyStats as $stat): ?>
                    <?php 
                    $total = ($stat['success_count'] ?? 0) + ($stat['failed_count'] ?? 0);
                    $rate = $total > 0 ? round(($stat['success_count'] / $total) * 100, 1) : 0;
                    ?>
                    <tr class="hover:bg-[#F5F5F5]">
                        <td class="px-4 py-2 text-sm text-[#212529]"><?= $stat['date'] ?></td>
                        <td class="px-4 py-2 text-sm text-right text-[#155724]"><?= number_format($stat['success_count']) ?></td>
                        <td class="px-4 py-2 text-sm text-right text-[#DC3545]"><?= number_format($stat['failed_count']) ?></td>
                        <td class="px-4 py-2 text-sm text-right">
                            <span class="<?= $rate >= 80 ? 'text-[#155724]' : ($rate >= 50 ? 'text-[#856404]' : 'text-[#DC3545]') ?>">
                                <?= $rate ?>%
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
