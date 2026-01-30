<!-- Hero Section -->
<section class="hero" id="hero">
    <div class="hero-carousel">
        <div class="hero-carousel-track">
            <?php if (!empty($heroBanners)): ?>
                <?php foreach ($heroBanners as $index => $banner): ?>
                <div class="hero-slide <?= $index === 0 ? 'active' : '' ?>" data-index="<?= $index ?>">
                    <?php if ($banner['media_type'] === 'video' && !empty($banner['video_path'])): ?>
                    <video class="hero-video" autoplay muted loop playsinline>
                        <source src="<?= url($banner['video_path']) ?>" type="video/mp4">
                    </video>
                    <?php else: ?>
                    <img src="<?= url($banner['image_path']) ?>" alt="<?= htmlspecialchars($banner['title']) ?>" class="hero-image">
                    <?php endif; ?>
                    <div class="hero-mark-wrapper">
                        <img src="<?= asset('images/frontend/hero-mark.jpg') ?>" alt="Hero Mark" class="hero-mark">
                        <a href="#about" class="btn-read-more">Read More ></a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- 預設輪播（無資料時） -->
                <div class="hero-slide active" data-index="0">
                    <video class="hero-video" autoplay muted loop playsinline>
                        <source src="<?= asset('images/frontend/首頁影片_compressed.mp4') ?>" type="video/mp4">
                    </video>
                    <div class="hero-mark-wrapper">
                        <img src="<?= asset('images/frontend/hero-mark.jpg') ?>" alt="Hero Mark" class="hero-mark">
                        <a href="#about" class="btn-read-more">Read More ></a>
                    </div>
                </div>
                <div class="hero-slide" data-index="1">
                    <img src="<?= asset('images/frontend/hero-bg.png') ?>" alt="Cibes 愛升達家用電梯" class="hero-image">
                    <div class="hero-mark-wrapper">
                        <img src="<?= asset('images/frontend/hero-mark.jpg') ?>" alt="Hero Mark" class="hero-mark">
                        <a href="#about" class="btn-read-more">Read More ></a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="hero-carousel-dots">
            <?php if (!empty($heroBanners)): ?>
                <?php foreach ($heroBanners as $index => $banner): ?>
                <span class="hero-dot <?= $index === 0 ? 'active' : '' ?>" data-index="<?= $index ?>"></span>
                <?php endforeach; ?>
            <?php else: ?>
                <span class="hero-dot active" data-index="0"></span>
                <span class="hero-dot" data-index="1"></span>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="about" id="about">
    <div class="about-header">
        <h2 class="about-title-en">
            Beyond Age,<br>
            Together for a Golden Life.
        </h2>
        <p class="about-title-zh">跨越年齡想像，邁出自由步伐</p>
        <p class="about-subtitle">在人生最從容的時刻，活出不被年齡定義的生活品味</p>
    </div>
    <div class="about-image">
        <img src="<?= asset('images/frontend/Cibes 瑞典工廠.jpg') ?>" alt="Cibes 瑞典工廠">
    </div>
    <div class="about-container">
        <div class="about-content-section">
            <div class="section-number">
                <span class="number">01</span>
                <span class="label">About Us</span>
            </div>
            <div class="about-content-wrapper">
                <div class="about-content-left">
                    <h3 class="about-content-title">業務遍及全球 瑞典專注電梯公司</h3>
                </div>
                <div class="about-content-right">
                    <p>Cibes Lift Group 的前身是一家於 1947 年在瑞典北部成立的小型電梯公司，如今已發展成全球最大的預製式、節省空間型電梯製的造商之一，其產品設計旨在安裝快速、易於升級。在瑞典、美國、中國和波蘭均設有生產基地，為遍布各大洲 70 多個國家和地區的客戶提供銷售和客戶支援服務。</p>
                </div>
            </div>
        </div>
    </div>
    <div class="about-features-row">
        <div class="about-elevator-image">
            <img src="<?= asset('images/frontend/Cibes 家用電梯.jpg') ?>" alt="Cibes 家用電梯">
        </div>
        <div class="about-features-content">
            <div class="section-number">
                <span class="number">02</span>
                <span class="label">Features</span>
            </div>
            <h3 class="about-features-title">源自瑞典的<br>高效能質感電梯</h3>
            <h4 class="about-features-subtitle">靜音螺桿驅動技術</h4>
            <p class="about-features-desc">Cibes 獨特的 EcoSilent 靜音螺桿驅動系統，不僅將電梯運行聲音降至約等同於圖書館的 40 分貝寧靜，同時還能節省約 45% 用電量，年耗電量比家用洗衣機還低，讓您在享受安全的移動體驗時，也能擁有最舒適寧靜的居家生活。</p>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features" id="features">
    <div class="features-background">
        <img src="<?= asset('images/frontend/hero-bg.png') ?>" alt="背景圖片">
        <div class="features-overlay"></div>
        <div class="features-arrow"></div>
    </div>
    <div class="features-content">
        <div class="features-carousel">
            <div class="carousel-track">
                <?php if (!empty($featuresBanners)): ?>
                    <?php foreach ($featuresBanners as $index => $banner): ?>
                    <div class="carousel-item" data-index="<?= $index ?>">
                        <div class="carousel-image">
                            <?php if (!empty($banner['link_url'])): ?>
                            <a href="<?= htmlspecialchars($banner['link_url']) ?>" target="<?= htmlspecialchars($banner['link_target'] ?? '_self') ?>">
                                <img src="<?= url($banner['image_path']) ?>" alt="<?= htmlspecialchars($banner['title']) ?>">
                            </a>
                            <?php else: ?>
                            <img src="<?= url($banner['image_path']) ?>" alt="<?= htmlspecialchars($banner['title']) ?>">
                            <?php endif; ?>
                        </div>
                        <div class="carousel-text">
                            <h3 class="carousel-title"><?= htmlspecialchars($banner['title']) ?></h3>
                            <p class="carousel-desc"><?= htmlspecialchars($banner['description'] ?? '') ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- 預設輪播（無資料時） -->
                    <div class="carousel-item" data-index="0">
                        <div class="carousel-image">
                            <img src="<?= asset('images/frontend/節省空間.jpg') ?>" alt="節省空間">
                        </div>
                        <div class="carousel-text">
                            <h3 class="carousel-title">節省空間、易於安裝配備</h3>
                            <p class="carousel-desc">無底坑、無機房設計，最小的佔地即可安裝，改造住宅結構需求低。</p>
                        </div>
                    </div>
                    <div class="carousel-item" data-index="1">
                        <div class="carousel-image">
                            <img src="<?= asset('images/frontend/高質感設計.jpg') ?>" alt="高質感設計">
                        </div>
                        <div class="carousel-text">
                            <h3 class="carousel-title">高質感設計、無障礙體驗</h3>
                            <p class="carousel-desc">鋼化雙層夾膠玻璃車廂、柔和的照明與簡易操作，兼顧美觀與使用友善，提升生活便利與住宅價值。</p>
                        </div>
                    </div>
                    <div class="carousel-item" data-index="2">
                        <div class="carousel-image">
                            <img src="<?= asset('images/frontend/轎底安全觸板.jpg') ?>" alt="轎底安全觸板">
                        </div>
                        <div class="carousel-text">
                            <h3 class="carousel-title">轎底安全觸板</h3>
                            <p class="carousel-desc">當電梯下降時，如果有任何東西進入電梯下面，一旦觸及安全板，電梯將自動停止，確保使用上的絕對安全。</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="carousel-dots">
                <?php if (!empty($featuresBanners)): ?>
                    <?php foreach ($featuresBanners as $index => $banner): ?>
                    <span class="dot <?= $index === 0 ? 'active' : '' ?>" data-index="<?= $index ?>"></span>
                    <?php endforeach; ?>
                <?php else: ?>
                    <span class="dot active" data-index="0"></span>
                    <span class="dot" data-index="1"></span>
                    <span class="dot" data-index="2"></span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Instruction Guide Section -->
<section class="instruction" id="instruction">
    <div class="container">
        <div class="instruction-header">
            <div class="section-number">
                <span class="number">03</span>
                <span class="label">Instruction Guide</span>
            </div>
            <h2 class="instruction-title">產品知識分享</h2>
        </div>
        <div class="instruction-grid">
            <?php if (!empty($pinnedKnowledge)): ?>
                <?php foreach ($pinnedKnowledge as $knowledge): ?>
                <a href="<?= url('/knowledge/' . $knowledge['id']) ?>" class="instruction-card">
                    <div class="instruction-card-image">
                        <?php if (!empty($knowledge['image_path'])): ?>
                        <img src="<?= url($knowledge['image_path']) ?>" alt="<?= htmlspecialchars($knowledge['title']) ?>">
                        <?php else: ?>
                        <img src="<?= asset('images/frontend/節省空間.jpg') ?>" alt="<?= htmlspecialchars($knowledge['title']) ?>">
                        <?php endif; ?>
                    </div>
                    <div class="instruction-card-content">
                        <h3 class="instruction-card-title"><?= htmlspecialchars($knowledge['title']) ?></h3>
                        <p class="instruction-card-desc"><?= htmlspecialchars(substr(strip_tags($knowledge['content'] ?? ''), 0, 80)) ?><?= strlen(strip_tags($knowledge['content'] ?? '')) > 80 ? '...' : '' ?></p>
                        <span class="instruction-card-more">READ MORE &gt;</span>
                    </div>
                </a>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- 預設卡片（無資料時） -->
                <a href="<?= url('/knowledge') ?>" class="instruction-card">
                    <div class="instruction-card-image">
                        <img src="<?= asset('images/frontend/節省空間.jpg') ?>" alt="EcoSilent 螺桿驅動系統">
                    </div>
                    <div class="instruction-card-content">
                        <h3 class="instruction-card-title">EcoSilent 螺桿驅動系統</h3>
                        <p class="instruction-card-desc">Ascenda 獨特的 EcoSilent 靜音螺桿驅動系統，採用創新螺桿技術，將運行聲音降至約等同於圖書館的 40 分貝寧靜。</p>
                        <span class="instruction-card-more">READ MORE &gt;</span>
                    </div>
                </a>
                <a href="<?= url('/knowledge') ?>" class="instruction-card">
                    <div class="instruction-card-image">
                        <img src="<?= asset('images/frontend/高質感設計.jpg') ?>" alt="9 個住宅電梯的好處">
                    </div>
                    <div class="instruction-card-content">
                        <h3 class="instruction-card-title">9 個住宅電梯的好處</h3>
                        <p class="instruction-card-desc">到了 2025 年，越來越多台灣家庭開始重新思考「居家便利性」與「未來生活的彈性」。</p>
                        <span class="instruction-card-more">READ MORE &gt;</span>
                    </div>
                </a>
                <a href="<?= url('/knowledge') ?>" class="instruction-card">
                    <div class="instruction-card-image">
                        <img src="<?= asset('images/frontend/轎底安全觸板.jpg') ?>" alt="避免常見的家用電梯安裝問題">
                    </div>
                    <div class="instruction-card-content">
                        <h3 class="instruction-card-title">避免常見的家用電梯安裝問題</h3>
                        <p class="instruction-card-desc">你是否正在考慮在家中安裝一部家用電梯一次能徹底改變生活的升級？</p>
                        <span class="instruction-card-more">READ MORE &gt;</span>
                    </div>
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Full Width Image Section -->
<section class="full-width-image">
    <img src="<?= asset('images/frontend/Cibes 展示空間.jpg') ?>" alt="Cibes 展示空間">
</section>

<!-- Contact Section -->
<section class="contact" id="contact">
    <div class="container">
        <div class="section-number">
            <span class="number">04</span>
            <span class="label">Showroom</span>
        </div>
        <h2 class="contact-section-title">專屬展示中心</h2>
        <div class="contact-wrapper">
            <div class="contact-info">
                <h3 class="contact-subtitle">眼見為憑</h3>
                <p>親臨展示中心，親身感受 Ascenda 愛升達家用電梯的順暢體驗與細膩質感。</p>
                <p>現場專人解說各項功能與設計細節，讓您更貼近理想的居家垂直生活。</p>
                <div class="contact-map">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3613.6668027484866!2d121.59158190000002!3d25.078586599999994!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3442ac8dd6d3e3f7%3A0xedd9dfbea2453798!2z6bS75bGV55uf5pyJ6ZmQ5YWs5Y-4!5e0!3m2!1szh-TW!2stw!4v1768990863001!5m2!1szh-TW!2stw" 
                        width="100%" 
                        height="300" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
            <div class="contact-form-wrapper">
                <h3 class="form-title">預約專人諮詢</h3>
                <form class="contact-form" id="contact-form">
                    <div class="form-row-inline">
                        <label for="name"><span class="required">*</span>姓　　名 </label>
                        <div class="form-input-group">
                            <input type="text" id="name" name="name" placeholder="請輸入姓名" required>
                            <select id="title" name="title" class="select-small">
                                <option value="先生">先生</option>
                                <option value="小姐">小姐</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row-inline">
                        <label><span class="required">*</span>諮詢需求</label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="inquiry" value="預約參觀" checked> 預約參觀
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="inquiry" value="電洽諮詢"> 電洽諮詢
                            </label>
                        </div>
                    </div>
                    <div class="form-row-inline form-field-visit" id="visit-time-row">
                        <label for="visit-time"><span class="required">*</span>參觀時間 </label>
                        <select id="visit-time" name="visit-time">
                            <option value="">請選擇您期望的參觀時段</option>
                            <option value="平日10:00-12:00">平日10:00 - 12:00</option>
                            <option value="平日13:00-15:00">平日13:00 - 15:00</option>
                            <option value="平日16:00-18:00">平日16:00 - 18:00</option>
                            <option value="其他">其他</option>
                        </select>
                    </div>
                    <div class="form-row-inline form-field-contact" id="contact-time-row">
                        <label for="contact-time"><span class="required">*</span>聯絡時間 </label>
                        <select id="contact-time" name="contact-time">
                            <option value="">請選擇您期望的電聯時段</option>
                            <option value="平日09:00-11:00">平日09:00 - 11:00</option>
                            <option value="平日13:00-15:00">平日13:00 - 15:00</option>
                            <option value="平日16:00-18:00">平日16:00 - 18:00</option>
                            <option value="平日19:00-20:00">平日19:00 - 20:00</option>
                        </select>
                    </div>
                    <div class="form-row-inline form-field-phone" id="phone-row">
                        <label for="phone"><span class="required">*</span>聯絡電話 </label>
                        <input type="tel" id="phone" name="phone" placeholder="請輸入可聯繫電話">
                    </div>
                    <div class="form-row-inline">
                        <label for="city" style="padding-left:8px;">所在城市</label>
                        <div class="form-input-group">
                            <select id="city" name="city">
                                <option value="台北市">台北市</option>
                                <option value="新北市">新北市</option>
                                <option value="基隆市">基隆市</option>
                                <option value="桃園市">桃園市</option>
                                <option value="新竹市">新竹市</option>
                                <option value="新竹縣">新竹縣</option>
                                <option value="苗栗縣">苗栗縣</option>
                                <option value="台中市">台中市</option>
                                <option value="彰化縣">彰化縣</option>
                                <option value="南投縣">南投縣</option>
                                <option value="雲林縣">雲林縣</option>
                                <option value="嘉義市">嘉義市</option>
                                <option value="嘉義縣">嘉義縣</option>
                                <option value="台南市">台南市</option>
                                <option value="高雄市">高雄市</option>
                                <option value="屏東縣">屏東縣</option>
                                <option value="宜蘭縣">宜蘭縣</option>
                                <option value="花蓮縣">花蓮縣</option>
                                <option value="台東縣">台東縣</option>
                                <option value="澎湖縣">澎湖縣</option>
                                <option value="金門縣">金門縣</option>
                                <option value="連江縣">連江縣</option>
                            </select>
                            <label for="family" class="inline-label">家庭成員</label>
                            <select id="family" name="family" class="select-small">
                                <option value="1">1 位</option>
                                <option value="2">2 位</option>
                                <option value="3">3 位</option>
                                <option value="4">4 位</option>
                                <option value="5+">5 位以上</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row-inline form-row-textarea">
                        <label for="message" style="padding-left:8px;">其他留言</label>
                        <textarea id="message" name="message" rows="5" placeholder="請簡單說明您的需求，以利聯繫時可給您完整的回應"></textarea>
                    </div>
                    <div class="form-submit">
                        <button type="submit" class="btn btn-primary">提交表單 <span class="arrow">&gt;</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
