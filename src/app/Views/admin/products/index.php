<?php $currentPage = 'products'; ?>

<!-- Header -->
<div class="mb-6">
    <h2 class="text-2xl font-bold text-[#212529]">產品維護</h2>
    <p class="text-[#6C757D]">選擇產品進行內容管理</p>
</div>

<!-- Products Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php if (empty($products)): ?>
    <div class="col-span-full">
        <div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm p-8 text-center">
            <i class="fas fa-box-open text-4xl text-[#ADB5BD] mb-4"></i>
            <p class="text-[#6C757D]">目前沒有產品資料</p>
            <p class="text-sm text-[#ADB5BD] mt-2">請先執行資料庫初始化腳本</p>
        </div>
    </div>
    <?php else: ?>
    <?php foreach ($products as $product): ?>
    <div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm overflow-hidden hover:shadow-md transition group">
        <!-- Product Image -->
        <div class="relative h-48 bg-[#F8F9FA]">
            <?php 
            $mainImage = null;
            if (!empty($product['main_image'])) {
                $mainImage = $product['main_image'];
            }
            ?>
            <?php if ($mainImage): ?>
            <img src="<?= url($mainImage) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>" 
                 class="w-full h-full object-cover">
            <?php else: ?>
            <div class="w-full h-full flex items-center justify-center">
                <i class="fas fa-box text-6xl text-[#DEE2E6]"></i>
            </div>
            <?php endif; ?>
            
            <!-- Status Badge -->
            <?php if ($product['status'] == 1): ?>
            <span class="absolute top-3 right-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#D4EDDA] text-[#155724]">
                <i class="fas fa-eye mr-1"></i>顯示中
            </span>
            <?php else: ?>
            <span class="absolute top-3 right-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#F8D7DA] text-[#721C24]">
                <i class="fas fa-eye-slash mr-1"></i>已停用
            </span>
            <?php endif; ?>
        </div>
        
        <!-- Product Info -->
        <div class="p-5">
            <h3 class="text-lg font-semibold text-[#212529] mb-2 group-hover:text-[#4A90D9] transition">
                <?= htmlspecialchars($product['product_name']) ?>
            </h3>
            
            <?php if (!empty($product['short_description'])): ?>
            <p class="text-sm text-[#6C757D] mb-4 line-clamp-2">
                <?= htmlspecialchars($product['short_description']) ?>
            </p>
            <?php endif; ?>
            
            <!-- Stats -->
            <div class="flex items-center space-x-4 text-sm text-[#6C757D] mb-4">
                <div class="flex items-center" title="產品明細">
                    <i class="fas fa-list-ul mr-1.5 text-[#4A90D9]"></i>
                    <span><?= $product['detail_count'] ?? 0 ?> 項明細</span>
                </div>
                <div class="flex items-center" title="Q&A">
                    <i class="fas fa-question-circle mr-1.5 text-[#28A745]"></i>
                    <span><?= $product['faq_count'] ?? 0 ?> 則Q&A</span>
                </div>
            </div>
            
            <div class="flex items-center text-sm text-[#6C757D] mb-4">
                <div class="flex items-center" title="技術手冊">
                    <i class="fas fa-file-pdf mr-1.5 text-[#DC3545]"></i>
                    <span><?= $product['manual_count'] ?? 0 ?> 份手冊</span>
                </div>
            </div>
            
            <!-- Action Button -->
            <a href="<?= url('/admin/products/edit/' . $product['id']) ?>" 
               class="block w-full text-center bg-[#4A90D9] text-white px-4 py-2.5 rounded-lg hover:bg-[#357ABD] transition">
                <i class="fas fa-edit mr-2"></i>管理內容
            </a>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
