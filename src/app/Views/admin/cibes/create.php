<?php $currentPage = 'cibes'; ?>

<!-- Header -->
<div class="flex items-center mb-6">
    <a href="<?= url('/admin/cibes') ?>" class="text-[#6C757D] hover:text-[#212529] mr-4">
        <i class="fas fa-arrow-left text-lg"></i>
    </a>
    <div>
        <h2 class="text-2xl font-bold text-[#212529]">新增 Cibes 品牌</h2>
        <p class="text-[#6C757D]">新增 Cibes 品牌資料</p>
    </div>
</div>

<form id="cibes-form" action="<?= url('/admin/cibes/store') ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
    
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
                        <label for="name" class="block text-sm font-medium text-[#212529] mb-1">
                            品牌名稱 <span class="text-[#DC3545]">*</span>
                        </label>
                        <input type="text" id="name" name="name" required
                               class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                               placeholder="請輸入品牌名稱">
                    </div>
                    
                    <div>
                        <label for="category" class="block text-sm font-medium text-[#212529] mb-1">
                            類別
                        </label>
                        <select id="category" name="category"
                                class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]">
                            <option value="">請選擇類別</option>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?= htmlspecialchars($cat['code_id']) ?>">
                                <?= htmlspecialchars($cat['code_name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label for="content" class="block text-sm font-medium text-[#212529] mb-1">
                            內容描述
                        </label>
                        <textarea id="content" name="content" rows="6"
                                  class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                                  placeholder="請輸入內容描述（選填）"></textarea>
                    </div>
                </div>
            </div>
            
            <!-- 圖片上傳 -->
            <div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm p-6">
                <h3 class="text-lg font-semibold text-[#212529] mb-4">
                    <i class="fas fa-image mr-2 text-[#4A90D9]"></i>品牌圖片
                </h3>
                
                <div class="space-y-4">
                    <div id="image-upload-area" class="border-2 border-dashed border-[#DEE2E6] rounded-lg p-8 text-center hover:border-[#4A90D9] transition cursor-pointer">
                        <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/gif,image/webp" class="hidden">
                        <div id="upload-placeholder">
                            <i class="fas fa-cloud-upload-alt text-4xl text-[#ADB5BD] mb-3"></i>
                            <p class="text-[#6C757D] mb-2">點擊或拖曳圖片到此處上傳</p>
                            <p class="text-xs text-[#ADB5BD]">支援 JPG、PNG、GIF、WEBP 格式，最大 5MB</p>
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
            
            <!-- 操作按鈕 -->
            <div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm p-6">
                <button type="submit" id="submit-btn" class="w-full bg-[#4A90D9] text-white px-4 py-3 rounded-lg hover:bg-[#357ABD] transition font-medium">
                    <i class="fas fa-save mr-2"></i>儲存
                </button>
                <a href="<?= url('/admin/cibes') ?>" class="block w-full text-center mt-3 px-4 py-3 bg-[#F8F9FA] text-[#212529] rounded-lg hover:bg-[#E9ECEF] border border-[#DEE2E6]">
                    <i class="fas fa-times mr-2"></i>取消
                </a>
            </div>
        </div>
    </div>
</form>

<script>
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
        // 驗證檔案類型
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            showToast('請上傳 JPG、PNG、GIF 或 WEBP 格式的圖片', 'error');
            return;
        }
        
        // 驗證檔案大小
        if (file.size > 5 * 1024 * 1024) {
            showToast('圖片大小不能超過 5MB', 'error');
            return;
        }
        
        // 預覽圖片
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
    
    // 表單提交
    document.getElementById('cibes-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('submit-btn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>儲存中...';
        
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
                submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>儲存';
            }
        } catch (error) {
            showToast('發生錯誤，請稍後再試', 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>儲存';
        }
    });
</script>
