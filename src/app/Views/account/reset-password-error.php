<div class="min-h-screen flex items-center justify-center bg-[#F8F9FA] py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-xl border border-[#E9ECEF] shadow-sm text-center">
        <div class="text-[#DC3545] text-6xl mb-4">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <h1 class="text-2xl font-bold text-[#212529]">連結無效</h1>
        <p class="text-[#6C757D]"><?= htmlspecialchars($message ?? '密碼重設連結無效或已過期') ?></p>
        
        <div class="space-y-4 mt-8">
            <a href="<?= url('/account/forgot-password') ?>" 
               class="block w-full py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-[#4A90D9] hover:bg-[#357ABD] text-center">
                重新申請密碼重設
            </a>
            <a href="<?= url('/account/login') ?>" class="block text-sm text-[#4A90D9] hover:text-[#357ABD]">
                <i class="fas fa-arrow-left mr-1"></i>返回登入
            </a>
        </div>
    </div>
</div>
