<!-- Q&A Section -->
<section class="faq-section faq-page" id="product-faq">
    <div class="container">
        <div class="faq-page-header">
            <span class="faq-tag">FAQ</span>
            <h1 class="faq-page-title">常見問題</h1>
            <p class="faq-page-desc">關於 Ascenda 愛升達家用電梯的常見問題解答，若有其他疑問歡迎直接聯繫我們。</p>
        </div>
        
        <?php if (!empty($faqs)): ?>
        <?php 
            // 將 FAQ 分成左右兩欄
            $totalFaqs = count($faqs);
            $halfCount = ceil($totalFaqs / 2);
            $leftFaqs = array_slice($faqs, 0, $halfCount);
            $rightFaqs = array_slice($faqs, $halfCount);
        ?>
        <div class="faq-grid">
            <!-- 左側欄 -->
            <div class="faq-column">
                <?php foreach ($leftFaqs as $index => $faq): ?>
                <div class="faq-item">
                    <div class="faq-question">
                        <span class="faq-number">Q<sub><?= $index + 1 ?></sub></span>
                        <h4><?= htmlspecialchars($faq['question']) ?></h4>
                    </div>
                    <div class="faq-answer">
                        <?= nl2br(htmlspecialchars($faq['answer'])) ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- 右側欄 -->
            <div class="faq-column">
                <?php foreach ($rightFaqs as $index => $faq): ?>
                <div class="faq-item">
                    <div class="faq-question">
                        <span class="faq-number">Q<sub><?= $halfCount + $index + 1 ?></sub></span>
                        <h4><?= htmlspecialchars($faq['question']) ?></h4>
                    </div>
                    <div class="faq-answer">
                        <?= nl2br(htmlspecialchars($faq['answer'])) ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php else: ?>
        <div class="faq-empty">
            <p>目前尚無常見問題，歡迎直接聯繫我們諮詢。</p>
        </div>
        <?php endif; ?>
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
