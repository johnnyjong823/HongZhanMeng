<?php $currentPage = 'banners'; ?>

<!-- Header -->
<div class="flex items-center mb-6">
    <a href="<?= url('/admin/banners') ?>" class="text-[#6C757D] hover:text-[#212529] mr-4">
        <i class="fas fa-arrow-left text-lg"></i>
    </a>
    <div>
        <h2 class="text-2xl font-bold text-[#212529]">新增輪播</h2>
        <p class="text-[#6C757D]">新增首頁輪播圖片或影片</p>
    </div>
</div>

<form id="banner-form" action="<?= url('/admin/banners/store') ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- 主要內容 -->
        <div class="lg:col-span-2 space-y-6">
            <!-- 輪播位置 -->
            <div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm p-6">
                <h3 class="text-lg font-semibold text-[#212529] mb-4">
                    <i class="fas fa-map-marker-alt mr-2 text-[#4A90D9]"></i>輪播位置 <span class="text-[#DC3545]">*</span>
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="relative cursor-pointer">
                        <input type="radio" name="position" value="hero" checked class="peer sr-only" onchange="updateFormByPosition()">
                        <div class="p-4 border-2 border-[#DEE2E6] rounded-lg peer-checked:border-[#4A90D9] peer-checked:bg-[#4A90D9] peer-checked:bg-opacity-5 transition">
                            <div class="flex items-center mb-2">
                                <div class="w-10 h-10 bg-[#4A90D9] bg-opacity-10 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-film text-[#4A90D9]"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-[#212529]">首頁主輪播</div>
                                    <div class="text-xs text-[#6C757D]">支援圖片或影片</div>
                                </div>
                            </div>
                            <p class="text-sm text-[#6C757D]">全寬顯示於首頁頂部，建議尺寸 1920 x 800</p>
                        </div>
                    </label>
                    
                    <label class="relative cursor-pointer">
                        <input type="radio" name="position" value="features" class="peer sr-only" onchange="updateFormByPosition()">
                        <div class="p-4 border-2 border-[#DEE2E6] rounded-lg peer-checked:border-[#28A745] peer-checked:bg-[#28A745] peer-checked:bg-opacity-5 transition">
                            <div class="flex items-center mb-2">
                                <div class="w-10 h-10 bg-[#28A745] bg-opacity-10 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-images text-[#28A745]"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-[#212529]">下方三圖輪播</div>
                                    <div class="text-xs text-[#6C757D]">僅支援圖片</div>
                                </div>
                            </div>
                            <p class="text-sm text-[#6C757D]">圖片+文字，建議尺寸 600 x 400</p>
                        </div>
                    </label>
                </div>
            </div>
            
            <!-- 基本資訊 -->
            <div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm p-6">
                <h3 class="text-lg font-semibold text-[#212529] mb-4">
                    <i class="fas fa-info-circle mr-2 text-[#4A90D9]"></i>基本資訊
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="title" class="block text-sm font-medium text-[#212529] mb-1">
                            標題 <span class="text-[#DC3545]">*</span>
                        </label>
                        <input type="text" id="title" name="title" required
                               class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                               placeholder="請輸入標題">
                    </div>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium text-[#212529] mb-1">
                            描述 <span id="desc-required" class="text-[#DC3545] hidden">*</span>
                        </label>
                        <textarea id="description" name="description" rows="3"
                                  class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                                  placeholder="請輸入描述（下方三圖輪播會顯示此文字）"></textarea>
                    </div>
                </div>
            </div>
            
            <!-- 媒體類型選擇（僅首頁主輪播顯示） -->
            <div id="media-type-section" class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm p-6">
                <h3 class="text-lg font-semibold text-[#212529] mb-4">
                    <i class="fas fa-photo-video mr-2 text-[#4A90D9]"></i>媒體類型 <span class="text-[#DC3545]">*</span>
                </h3>
                
                <div class="flex space-x-4 mb-4">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="media_type" value="image" checked class="form-radio text-[#4A90D9] focus:ring-[#4A90D9]" onchange="toggleMediaUpload()">
                        <span class="ml-2 text-[#212529]"><i class="fas fa-image mr-1"></i>圖片</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="media_type" value="video" class="form-radio text-[#4A90D9] focus:ring-[#4A90D9]" onchange="toggleMediaUpload()">
                        <span class="ml-2 text-[#212529]"><i class="fas fa-video mr-1"></i>影片</span>
                    </label>
                </div>
            </div>
            
            <!-- 圖片上傳 -->
            <div id="image-upload-section" class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm p-6">
                <h3 class="text-lg font-semibold text-[#212529] mb-4">
                    <i class="fas fa-image mr-2 text-[#4A90D9]"></i>輪播圖片 <span class="text-[#DC3545]">*</span>
                </h3>
                
                <div class="space-y-4">
                    <div id="image-upload-area" class="border-2 border-dashed border-[#DEE2E6] rounded-lg p-8 text-center hover:border-[#4A90D9] transition cursor-pointer">
                        <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/gif,image/webp" class="hidden">
                        <div id="upload-placeholder">
                            <i class="fas fa-cloud-upload-alt text-4xl text-[#ADB5BD] mb-3"></i>
                            <p class="text-[#6C757D] mb-2">點擊或拖曳圖片到此處上傳</p>
                            <p class="text-xs text-[#ADB5BD]">支援 JPG、PNG、GIF、WEBP 格式，最大 10MB（自動壓縮）</p>
                            <p class="text-xs text-[#ADB5BD] mt-1" id="image-size-hint">建議尺寸：1920 x 800 像素</p>
                        </div>
                        <div id="image-preview" class="hidden">
                            <img id="preview-img" src="" alt="" class="max-w-full max-h-64 mx-auto rounded-lg">
                            <button type="button" onclick="removeImage()" class="mt-3 text-[#DC3545] hover:text-[#a71d2a] text-sm">
                                <i class="fas fa-times mr-1"></i>移除圖片
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- 影片上傳 -->
            <div id="video-upload-section" class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm p-6 hidden">
                <h3 class="text-lg font-semibold text-[#212529] mb-4">
                    <i class="fas fa-video mr-2 text-[#6f42c1]"></i>輪播影片 <span class="text-[#DC3545]">*</span>
                </h3>
                
                <!-- 壓縮建議提示 -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <div class="flex items-start">
                        <i class="fas fa-lightbulb text-blue-500 mt-0.5 mr-3"></i>
                        <div class="text-sm">
                            <p class="font-medium text-blue-800 mb-1">建議先壓縮影片再上傳</p>
                            <p class="text-blue-600 mb-2">推薦使用以下免費線上工具壓縮影片，可大幅減少檔案大小：</p>
                            <div class="flex flex-wrap gap-2">
                                <a href="https://www.freeconvert.com/video-compressor" target="_blank" 
                                   class="inline-flex items-center px-2 py-1 bg-white border border-blue-300 rounded text-blue-700 hover:bg-blue-100 transition text-xs">
                                    <i class="fas fa-external-link-alt mr-1"></i>FreeConvert
                                </a>
                                <a href="https://clideo.com/compress-video" target="_blank" 
                                   class="inline-flex items-center px-2 py-1 bg-white border border-blue-300 rounded text-blue-700 hover:bg-blue-100 transition text-xs">
                                    <i class="fas fa-external-link-alt mr-1"></i>Clideo
                                </a>
                                <a href="https://www.veed.io/tools/video-compressor" target="_blank" 
                                   class="inline-flex items-center px-2 py-1 bg-white border border-blue-300 rounded text-blue-700 hover:bg-blue-100 transition text-xs">
                                    <i class="fas fa-external-link-alt mr-1"></i>VEED.IO
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div id="video-upload-area" class="border-2 border-dashed border-[#DEE2E6] rounded-lg p-8 text-center hover:border-[#6f42c1] transition cursor-pointer">
                        <input type="file" id="video" name="video" accept="video/mp4,video/webm,video/ogg" class="hidden">
                        <div id="video-upload-placeholder">
                            <i class="fas fa-film text-4xl text-[#ADB5BD] mb-3"></i>
                            <p class="text-[#6C757D] mb-2">點擊或拖曳影片到此處上傳</p>
                            <p class="text-xs text-[#ADB5BD]">支援 MP4、WEBM、OGG 格式，最大 50MB</p>
                            <p class="text-xs text-[#ADB5BD] mt-1">影片將自動播放、靜音、循環播放</p>
                        </div>
                        <div id="video-preview" class="hidden">
                            <video id="preview-video" src="" class="max-w-full max-h-64 mx-auto rounded-lg" controls></video>
                            <p id="video-size-info" class="text-xs text-gray-500 mt-2"></p>
                            <button type="button" onclick="removeVideo()" class="mt-3 text-[#DC3545] hover:text-[#a71d2a] text-sm">
                                <i class="fas fa-times mr-1"></i>移除影片
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- 連結設定 -->
            <div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm p-6">
                <h3 class="text-lg font-semibold text-[#212529] mb-4">
                    <i class="fas fa-link mr-2 text-[#4A90D9]"></i>連結設定
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="link_url" class="block text-sm font-medium text-[#212529] mb-1">
                            連結網址
                        </label>
                        <input type="url" id="link_url" name="link_url"
                               class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                               placeholder="https://">
                        <p class="text-xs text-[#ADB5BD] mt-1">點擊圖片時跳轉的網址（選填）</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-[#212529] mb-1">
                            開啟方式
                        </label>
                        <div class="flex space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="link_target" value="_self" checked
                                       class="form-radio text-[#4A90D9] focus:ring-[#4A90D9]">
                                <span class="ml-2 text-[#212529]">同一視窗</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="link_target" value="_blank"
                                       class="form-radio text-[#4A90D9] focus:ring-[#4A90D9]">
                                <span class="ml-2 text-[#212529]">新視窗</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- 側邊欄 -->
        <div class="space-y-6">
            <!-- 發布設定 -->
            <div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm p-6">
                <h3 class="text-lg font-semibold text-[#212529] mb-4">
                    <i class="fas fa-cog mr-2 text-[#4A90D9]"></i>發布設定
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-[#212529] mb-1">
                            狀態
                        </label>
                        <div class="flex space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="status" value="1" checked
                                       class="form-radio text-[#4A90D9] focus:ring-[#4A90D9]">
                                <span class="ml-2 text-[#212529]">顯示</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="status" value="0"
                                       class="form-radio text-[#4A90D9] focus:ring-[#4A90D9]">
                                <span class="ml-2 text-[#212529]">停用</span>
                            </label>
                        </div>
                    </div>
                    
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-[#212529] mb-1">
                            排序
                        </label>
                        <input type="number" id="sort_order" name="sort_order" min="0" 
                               value="<?= $nextSortOrder ?? 1 ?>"
                               class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]">
                        <p class="text-xs text-[#ADB5BD] mt-1">數字越小排越前面</p>
                    </div>
                </div>
            </div>
            
            <!-- 顯示期間 -->
            <div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm p-6">
                <h3 class="text-lg font-semibold text-[#212529] mb-4">
                    <i class="fas fa-calendar-alt mr-2 text-[#4A90D9]"></i>顯示期間
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-[#212529] mb-1">
                            開始日期
                        </label>
                        <input type="date" id="start_date" name="start_date"
                               class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]">
                        <p class="text-xs text-[#ADB5BD] mt-1">留空表示立即開始</p>
                    </div>
                    
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-[#212529] mb-1">
                            結束日期
                        </label>
                        <input type="date" id="end_date" name="end_date"
                               class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]">
                        <p class="text-xs text-[#ADB5BD] mt-1">留空表示永久顯示</p>
                    </div>
                </div>
            </div>
            
            <!-- 操作按鈕 -->
            <div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm p-6">
                <button type="submit" id="submit-btn" class="w-full bg-[#4A90D9] text-white px-4 py-3 rounded-lg hover:bg-[#357ABD] transition font-medium">
                    <i class="fas fa-save mr-2"></i>儲存
                </button>
                <a href="<?= url('/admin/banners') ?>" class="block w-full text-center mt-3 px-4 py-3 bg-[#F8F9FA] text-[#212529] rounded-lg hover:bg-[#E9ECEF] border border-[#DEE2E6]">
                    <i class="fas fa-times mr-2"></i>取消
                </a>
            </div>
        </div>
    </div>
</form>

<script>
    // 各位置的下一個排序號碼
    const sortOrders = {
        hero: <?= $nextSortOrderHero ?? 1 ?>,
        features: <?= $nextSortOrderFeatures ?? 1 ?>
    };
    
    // 根據位置更新表單
    function updateFormByPosition() {
        const position = document.querySelector('input[name="position"]:checked').value;
        const mediaTypeSection = document.getElementById('media-type-section');
        const videoUploadSection = document.getElementById('video-upload-section');
        const descRequired = document.getElementById('desc-required');
        const imageSizeHint = document.getElementById('image-size-hint');
        const sortOrderInput = document.getElementById('sort_order');
        
        // 更新排序號碼
        sortOrderInput.value = sortOrders[position];
        
        if (position === 'hero') {
            // 首頁主輪播：顯示媒體類型選擇
            mediaTypeSection.classList.remove('hidden');
            descRequired.classList.add('hidden');
            imageSizeHint.textContent = '建議尺寸：1920 x 800 像素';
            toggleMediaUpload();
        } else {
            // 下方三圖輪播：只能上傳圖片
            mediaTypeSection.classList.add('hidden');
            videoUploadSection.classList.add('hidden');
            document.getElementById('image-upload-section').classList.remove('hidden');
            document.querySelector('input[name="media_type"][value="image"]').checked = true;
            descRequired.classList.remove('hidden');
            imageSizeHint.textContent = '建議尺寸：600 x 400 像素';
        }
    }
    
    // 切換媒體上傳區域
    function toggleMediaUpload() {
        const mediaType = document.querySelector('input[name="media_type"]:checked').value;
        const imageSection = document.getElementById('image-upload-section');
        const videoSection = document.getElementById('video-upload-section');
        
        if (mediaType === 'video') {
            imageSection.classList.add('hidden');
            videoSection.classList.remove('hidden');
        } else {
            imageSection.classList.remove('hidden');
            videoSection.classList.add('hidden');
        }
    }
    
    // 圖片上傳處理
    const uploadArea = document.getElementById('image-upload-area');
    const fileInput = document.getElementById('image');
    const uploadPlaceholder = document.getElementById('upload-placeholder');
    const imagePreview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    
    uploadArea.addEventListener('click', () => fileInput.click());
    
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('border-[#4A90D9]', 'bg-blue-50');
    });
    
    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('border-[#4A90D9]', 'bg-blue-50');
    });
    
    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('border-[#4A90D9]', 'bg-blue-50');
        
        if (e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            handleFileSelect(e.dataTransfer.files[0]);
        }
    });
    
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length) {
            handleFileSelect(e.target.files[0]);
        }
    });
    
    function handleFileSelect(file) {
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            showToast('請上傳 JPG、PNG、GIF 或 WEBP 格式的圖片', 'error');
            return;
        }
        
        if (file.size > 10 * 1024 * 1024) {
            showToast('圖片大小不能超過 10MB', 'error');
            return;
        }
        
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImg.src = e.target.result;
            uploadPlaceholder.classList.add('hidden');
            imagePreview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
    
    function removeImage() {
        fileInput.value = '';
        previewImg.src = '';
        uploadPlaceholder.classList.remove('hidden');
        imagePreview.classList.add('hidden');
    }
    
    // 影片上傳處理
    const videoUploadArea = document.getElementById('video-upload-area');
    const videoInput = document.getElementById('video');
    const videoUploadPlaceholder = document.getElementById('video-upload-placeholder');
    const videoPreviewDiv = document.getElementById('video-preview');
    const previewVideo = document.getElementById('preview-video');
    
    videoUploadArea.addEventListener('click', () => videoInput.click());
    
    videoUploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        videoUploadArea.classList.add('border-[#6f42c1]', 'bg-purple-50');
    });
    
    videoUploadArea.addEventListener('dragleave', () => {
        videoUploadArea.classList.remove('border-[#6f42c1]', 'bg-purple-50');
    });
    
    videoUploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        videoUploadArea.classList.remove('border-[#6f42c1]', 'bg-purple-50');
        
        if (e.dataTransfer.files.length) {
            videoInput.files = e.dataTransfer.files;
            handleVideoSelect(e.dataTransfer.files[0]);
        }
    });
    
    videoInput.addEventListener('change', (e) => {
        if (e.target.files.length) {
            handleVideoSelect(e.target.files[0]);
        }
    });
    
    function handleVideoSelect(file) {
        const allowedTypes = ['video/mp4', 'video/webm', 'video/ogg'];
        if (!allowedTypes.includes(file.type)) {
            showToast('請上傳 MP4、WEBM 或 OGG 格式的影片', 'error');
            return;
        }
        
        const maxSize = 50 * 1024 * 1024; // 50MB
        if (file.size > maxSize) {
            const sizeMB = (file.size / 1024 / 1024).toFixed(1);
            showToast(`影片大小 ${sizeMB}MB 超過 50MB 限制，請先壓縮後再上傳`, 'error');
            return;
        }
        
        // 顯示檔案大小資訊
        const sizeMB = (file.size / 1024 / 1024).toFixed(2);
        document.getElementById('video-size-info').textContent = `檔案大小：${sizeMB} MB`;
        
        const reader = new FileReader();
        reader.onload = (e) => {
            previewVideo.src = e.target.result;
            videoUploadPlaceholder.classList.add('hidden');
            videoPreviewDiv.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
    
    function removeVideo() {
        videoInput.value = '';
        previewVideo.src = '';
        videoUploadPlaceholder.classList.remove('hidden');
        videoPreviewDiv.classList.add('hidden');
    }
    
    // 表單提交
    document.getElementById('banner-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('submit-btn');
        const mediaType = document.querySelector('input[name="media_type"]:checked').value;
        const position = document.querySelector('input[name="position"]:checked').value;
        
        // 前端驗證：檢查是否已上傳媒體檔案
        if (position === 'hero' && mediaType === 'video') {
            // 首頁主輪播 + 影片：檢查影片
            if (!videoInput.files || videoInput.files.length === 0) {
                showToast('請上傳影片', 'error');
                return;
            }
        } else {
            // 其他情況：檢查圖片
            if (!fileInput.files || fileInput.files.length === 0) {
                showToast('請上傳圖片', 'error');
                return;
            }
        }
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>儲存中...';
        
        try {
            const formData = new FormData(this);
            
            // 確保正確的檔案被加入 FormData
            if (position === 'hero' && mediaType === 'video') {
                // 移除可能存在的空白圖片欄位，確保影片檔案正確傳送
                if (videoInput.files && videoInput.files.length > 0) {
                    formData.delete('video');
                    formData.append('video', videoInput.files[0]);
                }
            } else {
                // 移除可能存在的空白影片欄位，確保圖片檔案正確傳送
                if (fileInput.files && fileInput.files.length > 0) {
                    formData.delete('image');
                    formData.append('image', fileInput.files[0]);
                }
            }
            
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                showToast(result.message, 'success');
                if (result.redirect) {
                    setTimeout(() => window.location.href = result.redirect, 1000);
                }
            } else {
                showToast(result.message, 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>儲存';
            }
        } catch (error) {
            showToast('發生錯誤，請稍後再試', 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>儲存';
        }
    });
    
    // 初始化
    updateFormByPosition();
</script>
