<?php
/**
 * 知識分享明細頁
 * 
 * @var array $knowledge 知識分享資料
 * @var string $categoryName 類別名稱
 */
?>

<!-- Full Width Image -->
<section class="knowledge-detail-banner">
    <?php if (!empty($knowledge['image_path'])): ?>
        <img src="<?= url($knowledge['image_path']) ?>" alt="<?= htmlspecialchars($knowledge['title']) ?>">
    <?php else: ?>
        <img src="<?= asset('images/frontend/knowledge-default.jpg') ?>" alt="<?= htmlspecialchars($knowledge['title']) ?>">
    <?php endif; ?>
</section>

<!-- Content Section -->
<section class="knowledge-detail-content">
    <div class="container">
        <!-- 標題 -->
        <h1 class="knowledge-detail-title"><?= htmlspecialchars($knowledge['title']) ?></h1>
        
        <!-- 內容 -->
        <div class="knowledge-detail-body">
            <?= safe_html($knowledge['content']) ?>
        </div>
        
        <!-- 返回按鈕 -->
        <div class="knowledge-detail-back">
            <a href="<?= url('/knowledge') ?>" class="btn-back">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
                返回知識分享
            </a>
        </div>
    </div>
</section>
