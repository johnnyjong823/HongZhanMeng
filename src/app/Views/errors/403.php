<div class="min-h-[60vh] flex items-center justify-center">
    <div class="text-center">
        <div class="text-[#DC3545] text-6xl mb-4">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h1 class="text-4xl font-bold text-[#212529] mb-2">403</h1>
        <h2 class="text-xl text-[#6C757D] mb-4">存取被拒絕</h2>
        <p class="text-[#6C757D] mb-8"><?= htmlspecialchars($message ?? '您沒有權限存取此頁面') ?></p>
        <a href="<?= url('/admin') ?>" class="inline-block px-6 py-3 bg-[#4A90D9] text-white rounded-lg hover:bg-[#357ABD]">
            <i class="fas fa-arrow-left mr-2"></i>返回後台
        </a>
    </div>
</div>
