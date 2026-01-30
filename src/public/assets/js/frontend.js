/**
 * Cibes 愛升達家用電梯 - 一頁式網站 JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all modules
    initMobileMenu();
    initSmoothScroll();
    initHeaderScroll();
    initBackToTop();
    initFormValidation();
    initScrollAnimations();
    initFeaturesCarousel();
    initHeroCarousel();
    initElevatorHotspots();
});

/**
 * Mobile Menu Toggle
 */
function initMobileMenu() {
    const menuToggle = document.getElementById('menuToggle');
    const mainNav = document.querySelector('.main-nav');
    const navLinks = document.querySelectorAll('.nav-menu .nav-link');
    const dropdownItems = document.querySelectorAll('.has-dropdown');
    const submenuItems = document.querySelectorAll('.has-submenu');

    if (!menuToggle || !mainNav) return;

    // Toggle menu
    menuToggle.addEventListener('click', function() {
        this.classList.toggle('active');
        mainNav.classList.toggle('active');
        document.body.classList.toggle('menu-open');
    });

    // Handle dropdown on mobile - toggle on click
    dropdownItems.forEach(item => {
        const link = item.querySelector('.nav-link');
        
        link.addEventListener('click', function(e) {
            // 只在行動裝置上阻止連結跳轉
            if (window.innerWidth <= 991) {
                e.preventDefault();
                item.classList.toggle('active');
            }
        });
    });

    // Handle submenu on mobile - toggle on click
    submenuItems.forEach(item => {
        const link = item.querySelector(':scope > a');
        
        link.addEventListener('click', function(e) {
            // 只在行動裝置上阻止連結跳轉並展開子選單
            if (window.innerWidth <= 991) {
                e.preventDefault();
                e.stopPropagation();
                item.classList.toggle('active');
            }
        });
    });

    // Close menu when clicking a nav link (non-dropdown)
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // 只關閉選單如果不是下拉選單的觸發連結
            const parent = this.closest('.has-dropdown');
            if (!parent && window.innerWidth <= 991) {
                menuToggle.classList.remove('active');
                mainNav.classList.remove('active');
                document.body.classList.remove('menu-open');
            }
        });
    });

    // Close menu when clicking dropdown sub-links (including submenu links)
    const dropdownLinks = document.querySelectorAll('.dropdown-menu a, .submenu a');
    dropdownLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // 如果是 has-submenu 的直接連結，在行動版不關閉
            const parent = this.closest('.has-submenu');
            if (parent && this === parent.querySelector(':scope > a') && window.innerWidth <= 991) {
                return;
            }
            
            if (window.innerWidth <= 991) {
                menuToggle.classList.remove('active');
                mainNav.classList.remove('active');
                document.body.classList.remove('menu-open');
                // 也關閉下拉選單
                dropdownItems.forEach(item => item.classList.remove('active'));
                submenuItems.forEach(item => item.classList.remove('active'));
            }
        });
    });

    // Close menu when clicking outside
    document.addEventListener('click', function(e) {
        if (!mainNav.contains(e.target) && !menuToggle.contains(e.target)) {
            menuToggle.classList.remove('active');
            mainNav.classList.remove('active');
            document.body.classList.remove('menu-open');
            // 也關閉下拉選單
            dropdownItems.forEach(item => item.classList.remove('active'));
            submenuItems.forEach(item => item.classList.remove('active'));
        }
    });
}

/**
 * Smooth Scroll for Anchor Links
 */
function initSmoothScroll() {
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    const headerHeight = document.querySelector('.header')?.offsetHeight || 80;

    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            if (href === '#') return;

            const target = document.querySelector(href);
            
            if (target) {
                e.preventDefault();
                const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerHeight;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
}

/**
 * Header Scroll Effect
 */
function initHeaderScroll() {
    const header = document.getElementById('header');
    
    if (!header) return;

    let lastScrollY = window.scrollY;
    let ticking = false;

    function updateHeader() {
        const scrollY = window.scrollY;

        // Add/remove scrolled class
        if (scrollY > 100) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }

        // Hide/show header on scroll
        if (scrollY > lastScrollY && scrollY > 200) {
            header.style.transform = 'translateY(-100%)';
        } else {
            header.style.transform = 'translateY(0)';
        }

        lastScrollY = scrollY;
        ticking = false;
    }

    window.addEventListener('scroll', function() {
        if (!ticking) {
            requestAnimationFrame(updateHeader);
            ticking = true;
        }
    });
}

/**
 * Back to Top Button
 */
function initBackToTop() {
    const backToTop = document.getElementById('back-to-top');
    
    if (!backToTop) return;

    // Show/hide button based on scroll position
    window.addEventListener('scroll', function() {
        if (window.scrollY > 500) {
            backToTop.classList.add('visible');
        } else {
            backToTop.classList.remove('visible');
        }
    });

    // Scroll to top on click
    backToTop.addEventListener('click', function(e) {
        e.preventDefault();
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

/**
 * Form Validation & Submit to Google Sheets
 */
function initFormValidation() {
    const form = document.getElementById('contact-form');
    
    if (!form) return;

    // ⚠️ 請將下方 URL 替換為您的 Google Apps Script Web App URL
    const GOOGLE_SCRIPT_URL = 'https://script.google.com/macros/s/AKfycbyC6RcLC11xXMIWLSVfDiXMto0hyAmPe8tLNk7MCjX3M86iWEku5oQpU2xItoNpv7yITg/exec';

    // 取得欄位元素
    const inquiryRadios = form.querySelectorAll('input[name="inquiry"]');
    const visitTimeRow = document.getElementById('visit-time-row');
    const phoneRow = document.getElementById('phone-row');
    const contactTimeRow = document.getElementById('contact-time-row');
    const visitTimeSelect = document.getElementById('visit-time');
    const phoneInput = document.getElementById('phone');
    const contactTimeSelect = document.getElementById('contact-time');

    // 根據諮詢需求切換欄位顯示
    function updateFieldsVisibility() {
        const selectedInquiry = form.querySelector('input[name="inquiry"]:checked')?.value;
        
        if (selectedInquiry === '預約參觀') {
            // 預約參觀：顯示參觀時間和聯絡電話
            visitTimeRow.style.display = '';
            phoneRow.style.display = '';
            contactTimeRow.style.display = 'none';
            
            // 清空隱藏欄位的值
            contactTimeSelect.value = '';
        } else if (selectedInquiry === '電洽諮詢') {
            // 電洽諮詢：顯示聯絡電話和聯絡時間
            visitTimeRow.style.display = 'none';
            phoneRow.style.display = '';
            contactTimeRow.style.display = '';
            
            // 清空隱藏欄位的值
            visitTimeSelect.value = '';
        }
    }

    // 監聽諮詢需求變更
    inquiryRadios.forEach(radio => {
        radio.addEventListener('change', updateFieldsVisibility);
    });

    // 初始化欄位顯示狀態
    updateFieldsVisibility();

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Get form data
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        // 根據諮詢需求動態驗證必填欄位
        const selectedInquiry = data.inquiry;
        let requiredFields = ['name', 'inquiry', 'phone'];  // 聯絡電話一律必填
        
        if (selectedInquiry === '預約參觀') {
            requiredFields.push('visit-time');
        } else if (selectedInquiry === '電洽諮詢') {
            requiredFields.push('contact-time');
        }

        let isValid = true;

        requiredFields.forEach(field => {
            const input = form.querySelector(`[name="${field}"]`);
            if (!input || !input.value.trim()) {
                isValid = false;
                if (input) {
                    input.classList.add('error');
                    input.addEventListener('input', function() {
                        this.classList.remove('error');
                    }, { once: true });
                    input.addEventListener('change', function() {
                        this.classList.remove('error');
                    }, { once: true });
                }
            }
        });

        // Phone validation (always required now)
        const phoneValue = phoneInput.value;
        if (phoneValue) {
            const phoneRegex = /^[0-9]{8,10}$/;
            if (!phoneRegex.test(phoneValue.replace(/\D/g, ''))) {
                isValid = false;
                phoneInput.classList.add('error');
            }
        }

        if (isValid) {
            // 顯示載入中狀態
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '提交中... <span class="arrow">&gt;</span>';

            try {
                // 提交資料到 Google Sheets
                const response = await submitToGoogleSheets(data, GOOGLE_SCRIPT_URL);
                
                if (response.success) {
                    showNotification('表單已成功提交！我們將盡快與您聯繫。', 'success');
                    form.reset();
                    // 重置後更新欄位顯示
                    updateFieldsVisibility();
                } else {
                    throw new Error(response.message || '提交失敗');
                }
            } catch (error) {
                console.error('Submit error:', error);
                showNotification('提交失敗，請稍後再試或直接來電聯繫。', 'error');
            } finally {
                // 恢復按鈕狀態
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        } else {
            showNotification('請填寫所有必填欄位', 'error');
        }
    });
}

/**
 * Submit form data to Google Sheets via Google Apps Script
 */
async function submitToGoogleSheets(data, scriptUrl) {
    // 準備要發送的資料
    const payload = {
        timestamp: new Date().toLocaleString('zh-TW', { timeZone: 'Asia/Taipei' }),
        name: data.name || '',
        title: data.title || '',
        inquiry: data.inquiry || '',
        visitTime: data['visit-time'] || '',
        phone: data.phone || '',
        contactTime: data['contact-time'] || '',
        city: data.city || '',
        family: data.family || '',
        message: data.message || ''
    };

    // 發送 POST 請求到 Google Apps Script
    const response = await fetch(scriptUrl, {
        method: 'POST',
        mode: 'no-cors', // Google Apps Script 需要 no-cors 模式
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(payload)
    });

    // 由於 no-cors 模式無法讀取回應，我們假設成功
    // 如果需要確認回應，可以改用 JSONP 或設定 CORS
    return { success: true };
}

/**
 * Show Notification
 */
function showNotification(message, type = 'info') {
    // Remove existing notification
    const existing = document.querySelector('.notification');
    if (existing) {
        existing.remove();
    }

    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <p>${message}</p>
        <button class="notification-close" aria-label="關閉">&times;</button>
    `;

    // Add styles
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        padding: 16px 48px 16px 20px;
        background-color: ${type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#17a2b8'};
        color: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        z-index: 9999;
        animation: slideIn 0.3s ease;
        max-width: 90%;
    `;

    // Add animation keyframes
    if (!document.querySelector('#notification-styles')) {
        const style = document.createElement('style');
        style.id = 'notification-styles';
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
            .notification-close {
                position: absolute;
                top: 50%;
                right: 12px;
                transform: translateY(-50%);
                background: none;
                border: none;
                color: white;
                font-size: 24px;
                cursor: pointer;
                line-height: 1;
                opacity: 0.8;
            }
            .notification-close:hover {
                opacity: 1;
            }
        `;
        document.head.appendChild(style);
    }

    document.body.appendChild(notification);

    // Close button handler
    notification.querySelector('.notification-close').addEventListener('click', function() {
        notification.style.animation = 'slideOut 0.3s ease forwards';
        setTimeout(() => notification.remove(), 300);
    });

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.style.animation = 'slideOut 0.3s ease forwards';
            setTimeout(() => notification.remove(), 300);
        }
    }, 5000);
}

/**
 * Scroll Animations
 */
function initScrollAnimations() {
    // Check if IntersectionObserver is supported
    if (!('IntersectionObserver' in window)) return;

    const animatedElements = document.querySelectorAll(
        '.feature-card, .size-card, .about-content, .installation-wrapper'
    );

    const observerOptions = {
        root: null,
        rootMargin: '0px 0px -50px 0px',
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Add initial styles
    if (!document.querySelector('#animation-styles')) {
        const style = document.createElement('style');
        style.id = 'animation-styles';
        style.textContent = `
            .feature-card,
            .size-card,
            .about-content,
            .installation-wrapper {
                opacity: 0;
                transform: translateY(30px);
                transition: opacity 0.6s ease, transform 0.6s ease;
            }
            .feature-card.animate-in,
            .size-card.animate-in,
            .about-content.animate-in,
            .installation-wrapper.animate-in {
                opacity: 1;
                transform: translateY(0);
            }
            /* Stagger animation for grid items */
            .feature-card:nth-child(2) { transition-delay: 0.1s; }
            .feature-card:nth-child(3) { transition-delay: 0.2s; }
            .feature-card:nth-child(4) { transition-delay: 0.3s; }
            .size-card:nth-child(2) { transition-delay: 0.1s; }
            .size-card:nth-child(3) { transition-delay: 0.2s; }
            .size-card:nth-child(4) { transition-delay: 0.3s; }
        `;
        document.head.appendChild(style);
    }

    animatedElements.forEach(el => observer.observe(el));
}

/**
 * Active Navigation Link
 */
function updateActiveNavLink() {
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-list a');
    const headerHeight = document.querySelector('.header')?.offsetHeight || 80;

    let current = '';

    sections.forEach(section => {
        const sectionTop = section.offsetTop - headerHeight - 100;
        const sectionHeight = section.offsetHeight;
        
        if (window.scrollY >= sectionTop && window.scrollY < sectionTop + sectionHeight) {
            current = section.getAttribute('id');
        }
    });

    navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === `#${current}`) {
            link.classList.add('active');
        }
    });
}

// Update active nav link on scroll
window.addEventListener('scroll', updateActiveNavLink);

/**
 * Features Carousel - Coverflow 3D 效果
 */
function initFeaturesCarousel() {
    const carousel = document.querySelector('.features-carousel');
    if (!carousel) return;

    const track = carousel.querySelector('.carousel-track');
    const items = Array.from(carousel.querySelectorAll('.carousel-item'));
    const dots = carousel.querySelectorAll('.carousel-dots .dot');
    const totalItems = items.length;
    let currentIndex = 0;
    let isAnimating = false;

    // 更新輪播狀態
    function updateCarousel() {
        items.forEach((item, index) => {
            // 移除所有狀態 class
            item.classList.remove('active', 'prev', 'next', 'hidden', 'far-left', 'far-right');
            
            // 計算與當前項目的相對位置
            let diff = index - currentIndex;
            
            // 處理循環
            if (diff > totalItems / 2) diff -= totalItems;
            if (diff < -totalItems / 2) diff += totalItems;
            
            if (diff === 0) {
                item.classList.add('active');
            } else if (diff === -1) {
                item.classList.add('prev');
            } else if (diff === 1) {
                item.classList.add('next');
            } else {
                item.classList.add('hidden');
                if (diff < -1) {
                    item.classList.add('far-left');
                } else {
                    item.classList.add('far-right');
                }
            }
        });
        
        // 更新 dots
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentIndex);
        });
    }

    // 下一張
    function nextSlide() {
        if (isAnimating) return;
        isAnimating = true;
        
        currentIndex = (currentIndex + 1) % totalItems;
        updateCarousel();
        
        setTimeout(() => {
            isAnimating = false;
        }, 600);
    }

    // 上一張
    function prevSlide() {
        if (isAnimating) return;
        isAnimating = true;
        
        currentIndex = (currentIndex - 1 + totalItems) % totalItems;
        updateCarousel();
        
        setTimeout(() => {
            isAnimating = false;
        }, 600);
    }

    // 跳到指定張
    function goToSlide(index) {
        if (index === currentIndex || isAnimating) return;
        isAnimating = true;
        
        currentIndex = index;
        updateCarousel();
        
        setTimeout(() => {
            isAnimating = false;
        }, 600);
    }

    // 點擊 dots 切換
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            goToSlide(index);
        });
    });
    
    // 點擊左右側項目切換
    items.forEach((item, index) => {
        item.addEventListener('click', () => {
            if (item.classList.contains('prev')) {
                prevSlide();
            } else if (item.classList.contains('next')) {
                nextSlide();
            }
        });
    });

    // 支援觸控滑動
    let touchStartX = 0;
    let touchEndX = 0;

    carousel.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });

    carousel.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    }, { passive: true });

    function handleSwipe() {
        const swipeThreshold = 50;
        const diff = touchStartX - touchEndX;
        
        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0) {
                nextSlide();
            } else {
                prevSlide();
            }
        }
    }

    // 初始化
    updateCarousel();
}

/**
 * Preload Images
 */
function preloadImages() {
    const images = document.querySelectorAll('img[data-src]');
    
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    imageObserver.unobserve(img);
                }
            });
        });

        images.forEach(img => imageObserver.observe(img));
    } else {
        // Fallback for browsers without IntersectionObserver
        images.forEach(img => {
            img.src = img.dataset.src;
            img.removeAttribute('data-src');
        });
    }
}

/**
 * Hero Carousel - 首頁輪播
 */
function initHeroCarousel() {
    const carousel = document.querySelector('.hero-carousel');
    if (!carousel) return;

    const slides = Array.from(carousel.querySelectorAll('.hero-slide'));
    const dots = carousel.querySelectorAll('.hero-carousel-dots .hero-dot');
    const totalSlides = slides.length;
    let currentIndex = 0;
    let isAnimating = false;

    // 動態調整 hero 區塊的 margin-top 和影片的 padding-top
    // 影片需要 padding-top 避免被 fixed header 蓋住
    // 圖片需要負 margin（延伸到 header 下方）
    // 手機版不需要 padding-top
    function adjustHeroMargin() {
        const hero = document.querySelector('.hero');
        const header = document.getElementById('header');
        const videoSlide = slides[0];
        
        if (!hero || !header) return;
        
        const headerHeight = header.offsetHeight;
        const isMobile = window.innerWidth <= 480; // 只有小型手機版不需要 padding-top
        
        if (currentIndex === 0) {
            // 第一張是影片，不要負 margin
            hero.style.marginTop = '0';
            if (videoSlide) {
                // 手機版不需要 padding-top，電腦版才需要
                if (isMobile) {
                    videoSlide.style.paddingTop = '0';
                    videoSlide.style.backgroundColor = '';
                } else {
                    videoSlide.style.paddingTop = headerHeight + 'px';
                    videoSlide.style.backgroundColor = '#FFF';
                }
            }
        } else {
            // 其他張是圖片，用負 margin 讓圖片延伸到 header 下方
            hero.style.marginTop = `-${headerHeight}px`;
            if (videoSlide) {
                videoSlide.style.paddingTop = '0';
            }
        }
    }

    // 更新輪播狀態
    function updateCarousel() {
        slides.forEach((slide, index) => {
            slide.classList.toggle('active', index === currentIndex);
            
            // 處理影片播放
            const video = slide.querySelector('video');
            if (video) {
                if (index === currentIndex) {
                    video.currentTime = 0;
                    video.play().catch(() => {});
                } else {
                    video.pause();
                }
            }
        });

        // 更新 dots
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentIndex);
        });
        
        // 調整 hero margin
        adjustHeroMargin();
    }

    // 跳到指定張
    function goToSlide(index) {
        if (index === currentIndex || isAnimating) return;
        isAnimating = true;

        currentIndex = index;
        updateCarousel();

        setTimeout(() => {
            isAnimating = false;
        }, 800);
    }

    // 點擊 dots 切換
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            goToSlide(index);
        });
    });

    // 支援觸控滑動
    let touchStartX = 0;
    let touchEndX = 0;

    carousel.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });

    carousel.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    }, { passive: true });

    function handleSwipe() {
        const swipeThreshold = 50;
        const diff = touchStartX - touchEndX;

        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0) {
                // 下一張
                if (isAnimating) return;
                isAnimating = true;
                currentIndex = (currentIndex + 1) % totalSlides;
                updateCarousel();
                setTimeout(() => { isAnimating = false; }, 800);
            } else {
                // 上一張
                if (isAnimating) return;
                isAnimating = true;
                currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
                updateCarousel();
                setTimeout(() => { isAnimating = false; }, 800);
            }
        }
    }

    // 初始化
    updateCarousel();
    
    // 視窗大小改變時重新調整
    window.addEventListener('resize', adjustHeroMargin);
}

/**
 * Elevator Hotspots - 電梯圖片互動熱點
 * 結構: .hotspot-group 包含 .hotspot-dot + .hotspot-connector + .hotspot-info
 * 手機版使用 modal 彈出視窗
 */
function initElevatorHotspots() {
    const hotspotWrapper = document.querySelector('.elevator-hotspot-wrapper');
    if (!hotspotWrapper) return;

    const hotspotGroups = hotspotWrapper.querySelectorAll('.hotspot-group');
    
    // 建立 Modal 元素（只建立一次）
    let modalOverlay = document.querySelector('.hotspot-modal-overlay');
    if (!modalOverlay) {
        modalOverlay = document.createElement('div');
        modalOverlay.className = 'hotspot-modal-overlay';
        modalOverlay.innerHTML = `
            <div class="hotspot-modal">
                <button class="hotspot-modal-close" aria-label="關閉">&times;</button>
                <h4 class="hotspot-modal-title"></h4>
                <p class="hotspot-modal-desc"></p>
            </div>
        `;
        document.body.appendChild(modalOverlay);
        
        // Modal 關閉事件
        const modalClose = modalOverlay.querySelector('.hotspot-modal-close');
        modalClose.addEventListener('click', closeModal);
        
        // 點擊背景關閉
        modalOverlay.addEventListener('click', function(e) {
            if (e.target === modalOverlay) {
                closeModal();
            }
        });
        
        // ESC 鍵關閉
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modalOverlay.classList.contains('active')) {
                closeModal();
            }
        });
    }
    
    const modalTitle = modalOverlay.querySelector('.hotspot-modal-title');
    const modalDesc = modalOverlay.querySelector('.hotspot-modal-desc');

    // 判斷是否為手機版（768px 以下）
    function isMobile() {
        return window.innerWidth <= 768;
    }

    // 開啟 Modal
    function openModal(title, desc) {
        modalTitle.textContent = title;
        modalDesc.textContent = desc;
        modalOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    // 關閉 Modal
    function closeModal() {
        modalOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    // 停用所有 hotspot groups
    function deactivateAllGroups() {
        hotspotGroups.forEach(group => {
            group.classList.remove('active');
        });
    }

    // 為每個 hotspot group 添加互動事件
    hotspotGroups.forEach(group => {
        const dot = group.querySelector('.hotspot-dot');
        const info = group.querySelector('.hotspot-info');

        // 點擊圓點
        if (dot) {
            dot.addEventListener('click', function(e) {
                e.stopPropagation();
                
                if (isMobile()) {
                    // 手機版：顯示 Modal
                    const title = info.querySelector('.hotspot-info-title')?.textContent || '';
                    const desc = info.querySelector('.hotspot-info-desc')?.textContent || '';
                    openModal(title, desc);
                } else {
                    // 平板/電腦版：切換 active 狀態
                    const isActive = group.classList.contains('active');
                    
                    // 先停用所有
                    deactivateAllGroups();
                    
                    // 如果之前不是啟動狀態，則啟動
                    if (!isActive) {
                        group.classList.add('active');
                    }
                }
            });
        }

        // 點擊說明卡片維持顯示狀態（僅平板/電腦版）
        if (info) {
            info.addEventListener('click', function(e) {
                e.stopPropagation();
                if (!isMobile()) {
                    group.classList.add('active');
                }
            });
        }
    });

    // 點擊其他地方關閉所有 hotspots（僅平板/電腦版）
    document.addEventListener('click', function(e) {
        if (!isMobile() && !hotspotWrapper.contains(e.target)) {
            deactivateAllGroups();
        }
    });
}
