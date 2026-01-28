<?php $currentPage = 'dashboard'; ?>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-[#EBF4FB] text-[#4A90D9]">
                <i class="fas fa-users text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-[#6C757D]">使用者數量</p>
                <p class="text-2xl font-semibold text-[#212529]"><?= number_format($stats['total_users'] ?? 0) ?></p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-[#D4EDDA] text-[#155724]">
                <i class="fas fa-sign-in-alt text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-[#6C757D]">今日登入</p>
                <p class="text-2xl font-semibold text-[#212529]"><?= number_format($stats['today_logins'] ?? 0) ?></p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-[#D1ECF1] text-[#0C5460]">
                <i class="fas fa-clipboard-list text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-[#6C757D]">操作紀錄</p>
                <p class="text-2xl font-semibold text-[#212529]"><?= number_format($stats['recent_actions'] ?? 0) ?></p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-[#FFF3CD] text-[#856404]">
                <i class="fas fa-user-clock text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-[#6C757D]">線上使用者</p>
                <p class="text-2xl font-semibold text-[#212529]"><?= number_format($stats['online_users'] ?? 0) ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Actions -->
    <div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm">
        <div class="px-6 py-4 border-b border-[#E9ECEF] flex justify-between items-center">
            <h3 class="text-lg font-semibold text-[#212529]">最近操作紀錄</h3>
            <a href="<?= url('/admin/action-logs') ?>" class="text-sm text-[#4A90D9] hover:text-[#357ABD]">查看全部</a>
        </div>
        <div class="p-6">
            <?php if (empty($recentActions)): ?>
            <p class="text-[#6C757D] text-center py-4">暫無紀錄</p>
            <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($recentActions as $action): ?>
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-[#EBF4FB] rounded-full flex items-center justify-center text-[#4A90D9] text-sm">
                        <i class="fas fa-history"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-[#212529]">
                            <span class="font-medium"><?= htmlspecialchars($action['username'] ?? 'Unknown') ?></span>
                            執行了 <span class="text-[#4A90D9]"><?= htmlspecialchars($action['controller'] . '/' . $action['action']) ?></span>
                        </p>
                        <p class="text-xs text-[#6C757D]"><?= $action['created_at'] ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Recent Logins -->
    <div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm">
        <div class="px-6 py-4 border-b border-[#E9ECEF] flex justify-between items-center">
            <h3 class="text-lg font-semibold text-[#212529]">最近登入紀錄</h3>
            <a href="<?= url('/admin/login-logs') ?>" class="text-sm text-[#4A90D9] hover:text-[#357ABD]">查看全部</a>
        </div>
        <div class="p-6">
            <?php if (empty($recentLogins)): ?>
            <p class="text-[#6C757D] text-center py-4">暫無紀錄</p>
            <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($recentLogins as $login): ?>
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-8 h-8 <?= ($login['login_status'] ?? 0) == 1 ? 'bg-[#D4EDDA] text-[#155724]' : 'bg-[#F8D7DA] text-[#721C24]' ?> rounded-full flex items-center justify-center text-sm">
                        <i class="fas <?= ($login['login_status'] ?? 0) == 1 ? 'fa-check' : 'fa-times' ?>"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-[#212529]">
                            <span class="font-medium"><?= htmlspecialchars($login['username'] ?? 'Unknown') ?></span>
                            登入<?= ($login['login_status'] ?? 0) == 1 ? '成功' : '失敗' ?>
                        </p>
                        <p class="text-xs text-[#6C757D]">
                            <?= htmlspecialchars($login['ip_address'] ?? '') ?> · <?= $login['login_at'] ?? '' ?>
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Quick Links -->
<div class="mt-6 bg-white rounded-lg border border-[#E9ECEF] shadow-sm p-6">
    <h3 class="text-lg font-semibold text-[#212529] mb-4">快速連結</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="<?= url('/admin/users/create') ?>" class="flex items-center p-4 bg-[#F8F9FA] rounded-lg hover:bg-[#EBF4FB] transition border border-[#E9ECEF]">
            <i class="fas fa-user-plus text-[#4A90D9] mr-3"></i>
            <span class="text-[#212529]">新增使用者</span>
        </a>
        <a href="<?= url('/admin/roles/create') ?>" class="flex items-center p-4 bg-[#F8F9FA] rounded-lg hover:bg-[#EBF4FB] transition border border-[#E9ECEF]">
            <i class="fas fa-user-shield text-[#4A90D9] mr-3"></i>
            <span class="text-[#212529]">新增角色</span>
        </a>
        <a href="<?= url('/admin/account/change-password') ?>" class="flex items-center p-4 bg-[#F8F9FA] rounded-lg hover:bg-[#EBF4FB] transition border border-[#E9ECEF]">
            <i class="fas fa-key text-[#4A90D9] mr-3"></i>
            <span class="text-[#212529]">修改密碼</span>
        </a>
        <a href="<?= url('/admin/dashboard/system-info') ?>" class="flex items-center p-4 bg-[#F8F9FA] rounded-lg hover:bg-[#EBF4FB] transition border border-[#E9ECEF]">
            <i class="fas fa-server text-[#4A90D9] mr-3"></i>
            <span class="text-[#212529]">系統資訊</span>
        </a>
    </div>
</div>
