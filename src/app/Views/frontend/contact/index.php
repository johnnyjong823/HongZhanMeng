<!-- Full Width Banner -->
<section class="contact-page-banner">
    <div class="contact-page-banner-background">
        <img src="<?= asset('images/products/banner1.jpg') ?>" alt="聯繫我們">
    </div>
    <div class="contact-page-banner-overlay"></div>
    <div class="contact-page-banner-content">
        <p class="contact-page-banner-label">CONTACT US</p>
        <h1 class="contact-page-banner-title">聯繫我們</h1>
    </div>
</section>

<!-- Intro Section - 左內容右標題 -->
<section class="contact-intro-section">
    <div class="container">
        <div class="contact-intro-wrapper">
            <div class="contact-intro-content">
                <p class="contact-intro-lead">照片可以傳遞資訊，但無法傳遞感動。</p>
                <p class="contact-intro-desc">我們打造了一座實際的體驗中心，在這裡，電梯不再是冰冷的目錄與規格，而是真實存在的安靜陪伴。邀請您親身蒞臨，感受這份來自瑞典的工藝精品。</p>
            </div>
            <div class="contact-intro-title-wrapper">
                <h2 class="contact-intro-title">預見，生活的模樣</h2>
                <p class="contact-intro-subtitle">THE SHOWROOM</p>
            </div>
        </div>
    </div>
</section>

<!-- Details Section - 五感體驗 -->
<section class="contact-details-section">
    <div class="contact-details-visual">
        <div class="contact-details-image">
            <img src="<?= asset('images/about/bg01.jpg') ?>" alt="五感體驗">
        </div>
    </div>
    <div class="container">
        <div class="contact-details-content">
            <p class="contact-details-label">THE DETAILS</p>
            <h2 class="contact-details-title">親身經歷的五感體驗</h2>
            <div class="contact-details-desc">
                <p class="contact-details-lead">真正的質感，藏在指尖與耳邊。</p>
                <p><span class="highlight">聽覺</span>：親耳聆聽 EcoSilent 驅動系統的運作，見證 45 分貝的寧靜體驗。</p>
                <p><span class="highlight">觸覺</span>：撫摸瑞典精工烤漆的溫潤觸感，感受控制面板按鍵的回饋力道。</p>
                <p><span class="highlight">視覺</span>：自然光與室內燈光的交織，欣賞全景鋼化雙層夾膠玻璃帶來的安全感與通透視野。</p>
            </div>
        </div>
    </div>
</section>

<!-- Planning Section - 理解您對家的想像 (反向排版) -->
<section class="contact-details-section reverse">
    <div class="contact-details-visual">
        <div class="contact-details-image">
            <img src="<?= asset('images/about/bg01.jpg') ?>" alt="理解您對家的想像">
        </div>
    </div>
    <div class="container">
        <div class="contact-details-content">
            <p class="contact-details-label">PLANNING YOUR HOME</p>
            <h2 class="contact-details-title">理解您對家的想像</h2>
            <div class="contact-details-desc">
                <p>一杯咖啡的時間，讓我們理解您對生活的需求。</p>
                <p>現場規劃顧問將陪您一起拼湊出最理想的配置，在動線、空間與生活美學之間，與您一同描繪未來的生活藍景。</p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="contact" id="contact">
    <div class="container">
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
