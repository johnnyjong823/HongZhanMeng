<?php 
$currentPage = 'banners';
use App\Models\Banner;

$banner = $banner ?? [];
$currentPosition = $banner['position'] ?? Banner::POSITION_HERO;
$currentMediaType = $banner['media_type'] ?? Banner::MEDIA_IMAGE;
?>

<!-- Header -->
<div class="flex items-center mb-6">
    <a href="<?= url('/admin/banners') ?>" class="text-[#6C757D] hover:text-[#212529] mr-4">
        <i class="fas fa-arrow-left text-lg"></i>
    </a>
    <div class="flex-1">
        <div class="flex items-center gap-3">
            <h2 class="text-2xl font-bold text-[#212529]">編輯輪播</h2>
            <?php if ($currentPosition === Banner::POSITION_HERO): ?>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                    <i class="fas fa-star mr-1"></i>首頁主輪播
                </span>
            <?php else: ?>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    <i class="fas fa-th mr-1"></i>下方三圖輪播
                </span>
            <?php endif; ?>
        </div>
        <p class="text-[#6C757D]">修改輪播資訊</p>
    </div>
</div>

<form id="banner-form" action="<?= url('/admin/banners/update/' . $banner['id']) ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
    <input type="hidden" name="position" value="<?= htmlspecialchars($currentPosition) ?>">
    <input type="hidden" name="media_type" id="media_type" value="<?= htmlspecialchars($currentMediaType) ?>">
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- 主要內容 -->
        <div class="lg:col-span-2 space-y-6">
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
                               value="<?= htmlspecialchars($banner['title'] ?? '') ?>"
                               class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                               placeholder="請輸入標題">
                    </div>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium text-[#212529] mb-1">
                            描述
                        </label>
                        <textarea id="description" name="description" rows="3"
                                  class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                                  placeholder="請輸入描述（選填）"><?= htmlspecialchars($banner['description'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>
            
            <!-- 媒體類型選擇（僅首頁主輪播可選） -->
            <?php if ($currentPosition === Banner::POSITION_HERO): ?>
            <div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm p-6">
                <h3 class="text-lg font-semibold text-[#212529] mb-4">
                    <i class="fas fa-photo-video mr-2 text-[#4A90D9]"></i>媒體類型
                </h3>
                
                <div class="flex gap-4">
                    <label class="media-type-option flex-1 flex items-center gap-3 px-4 py-3 rounded-lg border-2 cursor-pointer transition-all <?= $currentMediaType === Banner::MEDIA_IMAGE ? 'border-[#4A90D9] bg-blue-50' : 'border-[#DEE2E6] hover:border-gray-300' ?>" data-type="<?= Banner::MEDIA_IMAGE ?>">
                        <input type="radio" name="media_type_radio" value="<?= Banner::MEDIA_IMAGE ?>" 
                            <?= $currentMediaType === Banner::MEDIA_IMAGE ? 'checked' : '' ?> class="hidden">
                        <i class="fas fa-image text-2xl <?= $currentMediaType === Banner::MEDIA_IMAGE ? 'text-[#4A90D9]' : 'text-gray-400' ?>"></i>
                        <div>
                            <span class="font-medium <?= $currentMediaType === Banner::MEDIA_IMAGE ? 'text-[#4A90D9]' : 'text-gray-600' ?>">圖片</span>
                            <p class="text-xs text-gray-400">JPG、PNG、GIF、WebP</p>
                        </div>
                    </label>
                    <label class="media-type-option flex-1 flex items-center gap-3 px-4 py-3 rounded-lg border-2 cursor-pointer transition-all <?= $currentMediaType === Banner::MEDIA_VIDEO ? 'border-[#4A90D9] bg-blue-50' : 'border-[#DEE2E6] hover:border-gray-300' ?>" data-type="<?= Banner::MEDIA_VIDEO ?>">
                        <input type="radio" name="media_type_radio" value="<?= Banner::MEDIA_VIDEO ?>" 
                            <?= $currentMediaType === Banner::MEDIA_VIDEO ? 'checked' : '' ?> class="hidden">
                        <i class="fas fa-video text-2xl <?= $currentMediaType === Banner::MEDIA_VIDEO ? 'text-[#4A90D9]' : 'text-gray-400' ?>"></i>
                        <div>
                            <span class="font-medium <?= $currentMediaType === Banner::MEDIA_VIDEO ? 'text-[#4A90D9]' : 'text-gray-600' ?>">影片</span>
                            <p class="text-xs text-gray-400">MP4、WebM、OGG</p>
                        </div>
                    </label>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- 圖片上傳 -->
            <div id="image-upload-section" class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm p-6 <?= ($currentPosition === Banner::POSITION_HERO && $currentMediaType === Banner::MEDIA_VIDEO) ? 'hidden' : '' ?>">
                <h3 class="text-lg font-semibold text-[#212529] mb-4">
                    <i class="fas fa-image mr-2 text-[#4A90D9]"></i>輪播圖片
                </h3>
                
                <div class="space-y-4">
                    <!-- 目前圖片 -->
                    <?php if (!empty($banner['image_path'])): ?>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-[#212529] mb-2">目前圖片</label>
                        <img src="<?= url($banner['image_path']) ?>" alt="" 
                             class="max-w-full max-h-64 rounded-lg border border-[#E9ECEF] cursor-pointer hover:opacity-80"
                             onclick="previewImage('<?= url($banner['image_path']) ?>')">
                    </div>
                    <?php endif; ?>
                    
                    <div id="image-upload-area" class="border-2 border-dashed border-[#DEE2E6] rounded-lg p-6 text-center hover:border-[#4A90D9] transition cursor-pointer">
                        <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/gif,image/webp" class="hidden">
                        <div id="upload-placeholder">
                            <i class="fas fa-cloud-upload-alt text-3xl text-[#ADB5BD] mb-2"></i>
                            <p class="text-[#6C757D] text-sm mb-1">點擊或拖曳新圖片以更換</p>
                            <p class="text-xs text-[#ADB5BD]">支援 JPG、PNG、GIF、WEBP 格式，最大 10MB（自動壓縮）</p>
                        </div>
                        <div id="image-preview" class="hidden">
                            <img id="preview-img" src="" alt="" class="max-w-full max-h-48 mx-auto rounded-lg">
                            <button type="button" onclick="removeImage()" class="mt-2 text-[#DC3545] hover:text-[#a71d2a] text-sm">
                                <i class="fas fa-times mr-1"></i>取消更換
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- 影片上傳（僅首頁主輪播可用） -->
            <?php if ($currentPosition === Banner::POSITION_HERO): ?>
            <div id="video-upload-section" class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm p-6 <?= $currentMediaType === Banner::MEDIA_IMAGE ? 'hidden' : '' ?>">
                <h3 class="text-lg font-semibold text-[#212529] mb-4">
                    <i class="fas fa-film mr-2 text-[#4A90D9]"></i>輪播影片
                </h3>
                
                <!-- 壓縮建議提示 -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <div class="flex items-start">
                        <i class="fas fa-lightbulb text-blue-500 mt-0.5 mr-3"></i>
                        <div class="text-sm">
                            <p class="font-medium text-blue-800 mb-1">建議先壓縮影片再上傳</p>
                            <p class="text-blue-600 mb-2">推薦使用以下免費線上工具壓縮影片：</p>
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
                    <!-- 目前影片 -->
                    <?php if (!empty($banner['video_path'])): ?>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-[#212529] mb-2">目前影片</label>
                        <video class="max-w-full max-h-64 rounded-lg border border-[#E9ECEF]" controls>
                            <source src="<?= url($banner['video_path']) ?>" type="video/mp4">
                            您的瀏覽器不支援影片播放
                        </video>
                    </div>
                    <?php endif; ?>
                    
                    <div id="video-upload-area" class="border-2 border-dashed border-[#DEE2E6] rounded-lg p-6 text-center hover:border-[#4A90D9] transition cursor-pointer">
                        <input type="file" id="video" name="video" accept="video/mp4,video/webm,video/ogg" class="hidden">
                        <div id="video-upload-placeholder">
                            <i class="fas fa-film text-3xl text-[#ADB5BD] mb-2"></i>
                            <p class="text-[#6C757D] text-sm mb-1">點擊或拖曳新影片以更換</p>
                            <p class="text-xs text-[#ADB5BD]">支援 MP4、WebM、OGG 格式，最大 50MB</p>
                        </div>
                        <div id="video-preview" class="hidden">
                            <video id="preview-video" class="max-w-full max-h-48 mx-auto rounded-lg" controls></video>
                            <p id="video-size-info" class="text-xs text-gray-500 mt-2"></p>
                            <button type="button" onclick="removeVideo()" class="mt-2 text-[#DC3545] hover:text-[#a71d2a] text-sm">
                                <i class="fas fa-times mr-1"></i>取消更換
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
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
                               value="<?= htmlspecialchars($banner['link_url'] ?? '') ?>"
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
                                <input type="radio" name="link_target" value="_self" 
                                       <?= ($banner['link_target'] ?? '_self') === '_self' ? 'checked' : '' ?>
                                       class="form-radio text-[#4A90D9] focus:ring-[#4A90D9]">
                                <span class="ml-2 text-[#212529]">同一視窗</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="link_target" value="_blank"
                                       <?= ($banner['link_target'] ?? '_self') === '_blank' ? 'checked' : '' ?>
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
                                <input type="radio" name="status" value="1" 
                                       <?= $banner['status'] == 1 ? 'checked' : '' ?>
                                       class="form-radio text-[#4A90D9] focus:ring-[#4A90D9]">
                                <span class="ml-2 text-[#212529]">顯示</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="status" value="0"
                                       <?= $banner['status'] == 0 ? 'checked' : '' ?>
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
                               value="<?= $banner['sort_order'] ?? 0 ?>"
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
                               value="<?= $banner['start_date'] ?? '' ?>"
                               class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]">
                        <p class="text-xs text-[#ADB5BD] mt-1">留空表示立即開始</p>
                    </div>
                    
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-[#212529] mb-1">
                            結束日期
                        </label>
                        <input type="date" id="end_date" name="end_date"
                               value="<?= $banner['end_date'] ?? '' ?>"
                               class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]">
                        <p class="text-xs text-[#ADB5BD] mt-1">留空表示永久顯示</p>
                    </div>
                </div>
            </div>
            
            <!-- 資訊 -->
            <div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm p-6">
                <h3 class="text-lg font-semibold text-[#212529] mb-4">
                    <i class="fas fa-info mr-2 text-[#4A90D9]"></i>資訊
                </h3>
                
                <div class="text-sm text-[#6C757D] space-y-2">
                    <div class="flex justify-between">
                        <span>建立時間：</span>
                        <span><?= $banner['created_at'] ? date('Y/m/d H:i', strtotime($banner['created_at'])) : '-' ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span>更新時間：</span>
                        <span><?= $banner['updated_at'] ? date('Y/m/d H:i', strtotime($banner['updated_at'])) : '-' ?></span>
                    </div>
                </div>
            </div>
            
            <!-- 操作按鈕 -->
            <div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm p-6">
                <button type="submit" id="submit-btn" class="w-full bg-[#4A90D9] text-white px-4 py-3 rounded-lg hover:bg-[#357ABD] transition font-medium">
                    <i class="fas fa-save mr-2"></i>更新
                </button>
                <a href="<?= url('/admin/banners') ?>" class="block w-full text-center mt-3 px-4 py-3 bg-[#F8F9FA] text-[#212529] rounded-lg hover:bg-[#E9ECEF] border border-[#DEE2E6]">
                    <i class="fas fa-times mr-2"></i>取消
                </a>
            </div>
        </div>
    </div>
</form>

<!-- Image Preview Modal -->
<div id="preview-modal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black bg-opacity-75" onclick="closePreviewModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="relative max-w-4xl max-h-[90vh]">
            <button onclick="closePreviewModal()" class="absolute -top-10 right-0 text-white text-2xl hover:text-gray-300">
                <i class="fas fa-times"></i>
            </button>
            <img id="modal-preview-img" src="" alt="" class="max-w-full max-h-[85vh] rounded-lg shadow-xl">
        </div>
    </div>
</div>

<script>
    // 媒體類型切換
    document.querySelectorAll('.media-type-option').forEach(option => {
        option.addEventListener('click', function() {
            const type = this.dataset.type;
            document.getElementById('media_type').value = type;
            
            // 更新樣式
            document.querySelectorAll('.media-type-option').forEach(opt => {
                const isSelected = opt.dataset.type === type;
                opt.classList.toggle('border-[#4A90D9]', isSelected);
                opt.classList.toggle('bg-blue-50', isSelected);
                opt.classList.toggle('border-[#DEE2E6]', !isSelected);
                
                const icon = opt.querySelector('i');
                if (icon) {
                    icon.classList.toggle('text-[#4A90D9]', isSelected);
                    icon.classList.toggle('text-gray-400', !isSelected);
                }
                
                const text = opt.querySelector('span');
                if (text) {
                    text.classList.toggle('text-[#4A90D9]', isSelected);
                    text.classList.toggle('text-gray-600', !isSelected);
                }
            });
            
            // 切換上傳區塊
            const imageSection = document.getElementById('image-upload-section');
            const videoSection = document.getElementById('video-upload-section');
            
            if (type === 'image') {
                if (imageSection) imageSection.classList.remove('hidden');
                if (videoSection) videoSection.classList.add('hidden');
            } else {
                if (imageSection) imageSection.classList.add('hidden');
                if (videoSection) videoSection.classList.remove('hidden');
            }
        });
    });
    
    // 圖片上傳處理
    const uploadArea = document.getElementById('image-upload-area');
    const fileInput = document.getElementById('image');
    const uploadPlaceholder = document.getElementById('upload-placeholder');
    const imagePreview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    
    if (uploadArea) {
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
                handleImageSelect(e.dataTransfer.files[0]);
            }
        });
    }
    
    if (fileInput) {
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length) {
                handleImageSelect(e.target.files[0]);
            }
        });
    }
    
    function handleImageSelect(file) {
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
    const videoPreviewArea = document.getElementById('video-preview');
    const previewVideo = document.getElementById('preview-video');
    
    if (videoUploadArea) {
        videoUploadArea.addEventListener('click', () => videoInput.click());
        
        videoUploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            videoUploadArea.classList.add('border-[#4A90D9]', 'bg-blue-50');
        });
        
        videoUploadArea.addEventListener('dragleave', () => {
            videoUploadArea.classList.remove('border-[#4A90D9]', 'bg-blue-50');
        });
        
        videoUploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            videoUploadArea.classList.remove('border-[#4A90D9]', 'bg-blue-50');
            
            if (e.dataTransfer.files.length) {
                videoInput.files = e.dataTransfer.files;
                handleVideoSelect(e.dataTransfer.files[0]);
            }
        });
    }
    
    if (videoInput) {
        videoInput.addEventListener('change', (e) => {
            if (e.target.files.length) {
                handleVideoSelect(e.target.files[0]);
            }
        });
    }
    
    function handleVideoSelect(file) {
        const allowedTypes = ['video/mp4', 'video/webm', 'video/ogg'];
        if (!allowedTypes.includes(file.type)) {
            showToast('請上傳 MP4、WebM 或 OGG 格式的影片', 'error');
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
        const sizeInfo = document.getElementById('video-size-info');
        if (sizeInfo) sizeInfo.textContent = `檔案大小：${sizeMB} MB`;
        
        const url = URL.createObjectURL(file);
        previewVideo.src = url;
        videoUploadPlaceholder.classList.add('hidden');
        videoPreviewArea.classList.remove('hidden');
    }
    
    function removeVideo() {
        videoInput.value = '';
        previewVideo.src = '';
        videoUploadPlaceholder.classList.remove('hidden');
        videoPreviewArea.classList.add('hidden');
    }
    
    // 圖片預覽 Modal
    function previewImage(src) {
        document.getElementById('modal-preview-img').src = src;
        document.getElementById('preview-modal').classList.remove('hidden');
    }
    
    function closePreviewModal() {
        document.getElementById('preview-modal').classList.add('hidden');
    }
    
    // 表單提交
    document.getElementById('banner-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('submit-btn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>更新中...';
        
        try {
            const formData = new FormData(this);
            
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
                submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>更新';
            }
        } catch (error) {
            showToast('發生錯誤，請稍後再試', 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>更新';
        }
    });
    
    // ESC 關閉預覽
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closePreviewModal();
        }
    });
</script>
