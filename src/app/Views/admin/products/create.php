<?php $currentPage = 'products'; ?>

<!-- Header -->
<div class="mb-6">
    <div class="flex items-center space-x-2 text-sm text-[#6C757D] mb-2">
        <a href="<?= url('/admin/products') ?>" class="hover:text-[#4A90D9]">產品維護</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-[#212529]">新增產品</span>
    </div>
    <h2 class="text-2xl font-bold text-[#212529]">新增產品</h2>
</div>

<!-- Form -->
<div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm">
    <form id="product-form" class="p-6 space-y-6">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        
        <!-- 基本資訊 -->
        <div class="border-b border-[#E9ECEF] pb-6">
            <h3 class="text-lg font-semibold text-[#212529] mb-4">
                <i class="fas fa-info-circle text-[#4A90D9] mr-2"></i>基本資訊
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- 產品名稱 -->
                <div class="md:col-span-2">
                    <label for="product_name" class="block text-sm font-medium text-[#212529] mb-1">
                        產品名稱 <span class="text-[#DC3545]">*</span>
                    </label>
                    <input type="text" id="product_name" name="product_name" required
                           class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                           placeholder="請輸入產品名稱">
                </div>
                
                <!-- 產品編號 -->
                <div>
                    <label for="product_code" class="block text-sm font-medium text-[#212529] mb-1">產品編號</label>
                    <input type="text" id="product_code" name="product_code"
                           class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                           placeholder="請輸入產品編號">
                </div>
                
                <!-- 類別 -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-[#212529] mb-1">產品類別</label>
                    <select id="category_id" name="category_id"
                            class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]">
                        <option value="">請選擇類別</option>
                        <?php foreach ($categories ?? [] as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['code_id']) ?>"><?= htmlspecialchars($cat['code_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- 尺寸 -->
                <div>
                    <label for="size" class="block text-sm font-medium text-[#212529] mb-1">尺寸</label>
                    <input type="text" id="size" name="size"
                           class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                           placeholder="例如: 100cm x 50cm x 30cm">
                </div>
                
                <!-- 規格型號 -->
                <div>
                    <label for="model" class="block text-sm font-medium text-[#212529] mb-1">規格型號</label>
                    <input type="text" id="model" name="model"
                           class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                           placeholder="請輸入規格型號">
                </div>
                
                <!-- 狀態 -->
                <div>
                    <label for="status" class="block text-sm font-medium text-[#212529] mb-1">狀態</label>
                    <select id="status" name="status"
                            class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]">
                        <option value="1">顯示</option>
                        <option value="0">停用</option>
                    </select>
                </div>
                
                <!-- 排序 -->
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-[#212529] mb-1">排序</label>
                    <input type="number" id="sort_order" name="sort_order" value="0"
                           class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                           placeholder="數字越小越前面">
                </div>
            </div>
        </div>
        
        <!-- 詳細內容 -->
        <div class="border-b border-[#E9ECEF] pb-6">
            <h3 class="text-lg font-semibold text-[#212529] mb-4">
                <i class="fas fa-file-alt text-[#4A90D9] mr-2"></i>詳細內容
            </h3>
            
            <!-- 詳細介紹 -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-[#212529] mb-1">詳細介紹</label>
                <textarea id="description" name="description" rows="5"
                          class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                          placeholder="請輸入產品詳細介紹"></textarea>
            </div>
            
            <!-- 安裝說明 -->
            <div class="mb-6">
                <label for="installation" class="block text-sm font-medium text-[#212529] mb-1">安裝說明</label>
                <textarea id="installation" name="installation" rows="4"
                          class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                          placeholder="請輸入安裝說明"></textarea>
            </div>
            
            <!-- 常見問題 -->
            <div>
                <label for="faq" class="block text-sm font-medium text-[#212529] mb-1">常見問題</label>
                <textarea id="faq" name="faq" rows="4"
                          class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                          placeholder="Q: 問題內容&#10;A: 回答內容"></textarea>
            </div>
        </div>
        
        <!-- 提示訊息 -->
        <div class="bg-[#D1ECF1] text-[#0C5460] rounded-lg p-4">
            <i class="fas fa-info-circle mr-2"></i>
            請先儲存產品基本資訊後，再上傳圖片和手冊檔案。
        </div>
        
        <!-- Actions -->
        <div class="flex justify-end space-x-4 pt-6 border-t border-[#E9ECEF]">
            <a href="<?= url('/admin/products') ?>" class="px-6 py-2 bg-[#F8F9FA] text-[#212529] rounded-lg hover:bg-[#E9ECEF] border border-[#DEE2E6]">
                取消
            </a>
            <button type="submit" id="submit-btn" class="px-6 py-2 bg-[#4A90D9] text-white rounded-lg hover:bg-[#357ABD]">
                <span id="btn-text">建立產品</span>
                <span id="btn-loading" class="hidden">
                    <i class="fas fa-spinner fa-spin mr-2"></i>處理中...
                </span>
            </button>
        </div>
        
        <div id="error-message" class="hidden p-4 bg-[#F8D7DA] text-[#721C24] rounded-lg"></div>
    </form>
</div>

<script>
    document.getElementById('product-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('submit-btn');
        const btnText = document.getElementById('btn-text');
        const btnLoading = document.getElementById('btn-loading');
        const errorMessage = document.getElementById('error-message');
        
        submitBtn.disabled = true;
        btnText.classList.add('hidden');
        btnLoading.classList.remove('hidden');
        errorMessage.classList.add('hidden');
        
        try {
            const formData = new FormData(this);
            const data = {
                csrf_token: formData.get('csrf_token'),
                product_name: formData.get('product_name'),
                product_code: formData.get('product_code'),
                category_id: formData.get('category_id'),
                size: formData.get('size'),
                model: formData.get('model'),
                description: formData.get('description'),
                installation: formData.get('installation'),
                faq: formData.get('faq'),
                status: formData.get('status'),
                sort_order: formData.get('sort_order')
            };
            
            const response = await fetch('<?= url('/admin/products/store') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                showToast(result.message, 'success');
                setTimeout(() => {
                    window.location.href = result.redirect || '<?= url('/admin/products') ?>';
                }, 1000);
            } else {
                errorMessage.textContent = result.message;
                errorMessage.classList.remove('hidden');
            }
        } catch (error) {
            errorMessage.textContent = '發生錯誤，請稍後再試';
            errorMessage.classList.remove('hidden');
        } finally {
            submitBtn.disabled = false;
            btnText.classList.remove('hidden');
            btnLoading.classList.add('hidden');
        }
    });
</script>
