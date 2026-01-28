<!-- Full Width Banner -->
<section class="knowledge-banner">
    <div class="knowledge-banner-background">
        <img src="<?= asset('images/products/banner1.jpg') ?>" alt="知識分享">
    </div>
    <div class="knowledge-banner-overlay"></div>
    <div class="knowledge-banner-content">
        <p class="knowledge-banner-label">KNOWLEDGE</p>
        <h1 class="knowledge-banner-title">知識分享</h1>
    </div>
</section>

<!-- Intro Section - 左內容右標題 -->
<section class="knowledge-intro-section">
    <div class="container">
        <div class="knowledge-intro-wrapper">
            <div class="knowledge-intro-content">
                <p class="knowledge-intro-desc">選擇家用電梯，是一次慎重的決定。我們分享關於 Ascenda 家用小電梯的專業知識與設計靈感，陪您一同規劃，在了解所有細節之後，為自己與家人做出一份最安心的選擇。</p>
            </div>
            <div class="knowledge-intro-title-wrapper">
                <h2 class="knowledge-intro-title">Ascenda 居家生活指南</h2>
                <p class="knowledge-intro-subtitle">LIVING WELL AT HOME</p>
            </div>
        </div>
    </div>
</section>

<!-- Knowledge List Section -->
<section class="knowledge-list-section">
    <div class="container">
        <?php if (empty($knowledgeList)): ?>
            <div class="knowledge-empty">
                <p>目前暫無知識分享內容，敬請期待！</p>
            </div>
        <?php else: ?>
            <div class="knowledge-grid">
                <?php foreach ($knowledgeList as $item): ?>
                    <a href="<?= url('/knowledge/' . $item['id']) ?>" class="knowledge-card">
                        <div class="knowledge-card-image">
                            <?php if (!empty($item['image_path'])): ?>
                                <img src="<?= url($item['image_path']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                            <?php else: ?>
                                <img src="<?= asset('images/frontend/knowledge-default.jpg') ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                            <?php endif; ?>
                        </div>
                        <div class="knowledge-card-content">
                            <h3 class="knowledge-card-title"><?= htmlspecialchars($item['title']) ?></h3>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
