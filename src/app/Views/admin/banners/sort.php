<?php $currentPage = 'banners'; ?>

<!-- Header -->
<div class="flex items-center mb-6">
    <a href="<?= url('/admin/banners') ?>" class="text-[#6C757D] hover:text-[#212529] mr-4">
        <i class="fas fa-arrow-left text-lg"></i>
    </a>
    <div class="flex-1">
        <h2 class="text-2xl font-bold text-[#212529]">輪播圖片排序</h2>
        <p class="text-[#6C757D]">拖曳調整輪播圖片順序</p>
    </div>
    <button onclick="saveSort()" id="save-btn" class="bg-[#4A90D9] text-white px-4 py-2 rounded-lg hover:bg-[#357ABD] transition disabled:opacity-50">
        <i class="fas fa-save mr-2"></i>儲存排序
    </button>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- 首頁主輪播 -->
    <div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm p-6">
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 bg-[#4A90D9] bg-opacity-10 rounded-lg flex items-center justify-center mr-3">
                <i class="fas fa-film text-[#4A90D9]"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-[#212529]">首頁主輪播</h3>
                <p class="text-xs text-[#6C757D]">支援圖片或影片</p>
            </div>
        </div>
        
        <ul id="hero-sortable-list" class="sortable-list space-y-3" data-position="hero">
            <?php if (!empty($heroBanners)): ?>
            <?php foreach ($heroBanners as $index => $banner): ?>
            <li class="sortable-item bg-[#F8F9FA] border border-[#E9ECEF] rounded-lg p-3 cursor-move hover:bg-white hover:shadow-sm transition" 
                data-id="<?= $banner['id'] ?>">
                <div class="flex items-center space-x-3">
                    <div class="drag-handle text-[#ADB5BD] hover:text-[#6C757D]">
                        <i class="fas fa-grip-vertical"></i>
                    </div>
                    
                    <div class="sort-number w-7 h-7 bg-[#4A90D9] text-white rounded-full flex items-center justify-center text-xs font-medium">
                        <?= $index + 1 ?>
                    </div>
                    
                    <?php if (($banner['media_type'] ?? 'image') === 'video' && !empty($banner['video_path'])): ?>
                    <div class="w-16 h-10 bg-[#212529] rounded flex items-center justify-center">
                        <i class="fas fa-play text-white text-xs"></i>
                    </div>
                    <?php elseif (!empty($banner['image_path'])): ?>
                    <img src="<?= url($banner['image_path']) ?>" alt="" 
                         class="w-16 h-10 object-cover rounded border border-[#E9ECEF]">
                    <?php else: ?>
                    <div class="w-16 h-10 bg-[#E9ECEF] rounded flex items-center justify-center">
                        <i class="fas fa-image text-[#ADB5BD] text-xs"></i>
                    </div>
                    <?php endif; ?>
                    
                    <div class="flex-1 min-w-0">
                        <div class="font-medium text-[#212529] text-sm truncate"><?= htmlspecialchars($banner['title']) ?></div>
                    </div>
                    
                    <?php if ($banner['status'] == 1): ?>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-[#D4EDDA] text-[#155724]">
                        顯示
                    </span>
                    <?php else: ?>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-[#F8D7DA] text-[#721C24]">
                        停用
                    </span>
                    <?php endif; ?>
                </div>
            </li>
            <?php endforeach; ?>
            <?php else: ?>
            <li class="text-center py-6 text-[#6C757D]">
                <i class="fas fa-images text-2xl mb-2"></i>
                <p class="text-sm">沒有輪播圖片</p>
            </li>
            <?php endif; ?>
        </ul>
    </div>
    
    <!-- 下方三圖輪播 -->
    <div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm p-6">
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 bg-[#28A745] bg-opacity-10 rounded-lg flex items-center justify-center mr-3">
                <i class="fas fa-images text-[#28A745]"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-[#212529]">下方三圖輪播</h3>
                <p class="text-xs text-[#6C757D]">圖片+文字</p>
            </div>
        </div>
        
        <ul id="features-sortable-list" class="sortable-list space-y-3" data-position="features">
            <?php if (!empty($featuresBanners)): ?>
            <?php foreach ($featuresBanners as $index => $banner): ?>
            <li class="sortable-item bg-[#F8F9FA] border border-[#E9ECEF] rounded-lg p-3 cursor-move hover:bg-white hover:shadow-sm transition" 
                data-id="<?= $banner['id'] ?>">
                <div class="flex items-center space-x-3">
                    <div class="drag-handle text-[#ADB5BD] hover:text-[#6C757D]">
                        <i class="fas fa-grip-vertical"></i>
                    </div>
                    
                    <div class="sort-number w-7 h-7 bg-[#28A745] text-white rounded-full flex items-center justify-center text-xs font-medium">
                        <?= $index + 1 ?>
                    </div>
                    
                    <?php if (!empty($banner['image_path'])): ?>
                    <img src="<?= url($banner['image_path']) ?>" alt="" 
                         class="w-16 h-10 object-cover rounded border border-[#E9ECEF]">
                    <?php else: ?>
                    <div class="w-16 h-10 bg-[#E9ECEF] rounded flex items-center justify-center">
                        <i class="fas fa-image text-[#ADB5BD] text-xs"></i>
                    </div>
                    <?php endif; ?>
                    
                    <div class="flex-1 min-w-0">
                        <div class="font-medium text-[#212529] text-sm truncate"><?= htmlspecialchars($banner['title']) ?></div>
                    </div>
                    
                    <?php if ($banner['status'] == 1): ?>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-[#D4EDDA] text-[#155724]">
                        顯示
                    </span>
                    <?php else: ?>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-[#F8D7DA] text-[#721C24]">
                        停用
                    </span>
                    <?php endif; ?>
                </div>
            </li>
            <?php endforeach; ?>
            <?php else: ?>
            <li class="text-center py-6 text-[#6C757D]">
                <i class="fas fa-images text-2xl mb-2"></i>
                <p class="text-sm">沒有輪播圖片</p>
            </li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<style>
    .sortable-item.dragging {
        opacity: 0.5;
        background: #E9ECEF;
    }
    .sortable-item.drag-over {
        border-color: #4A90D9;
        border-style: dashed;
    }
</style>

<script>
    let hasChanges = false;
    
    // 初始化所有可排序列表
    document.querySelectorAll('.sortable-list').forEach(list => {
        initSortable(list);
    });
    
    function initSortable(list) {
        let draggedItem = null;
        
        list.querySelectorAll('.sortable-item').forEach(item => {
            item.setAttribute('draggable', true);
            
            item.addEventListener('dragstart', function(e) {
                draggedItem = this;
                this.classList.add('dragging');
                e.dataTransfer.effectAllowed = 'move';
            });
            
            item.addEventListener('dragend', function(e) {
                this.classList.remove('dragging');
                list.querySelectorAll('.sortable-item').forEach(i => {
                    i.classList.remove('drag-over');
                });
            });
            
            item.addEventListener('dragover', function(e) {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
                if (this !== draggedItem) {
                    this.classList.add('drag-over');
                }
            });
            
            item.addEventListener('dragleave', function(e) {
                this.classList.remove('drag-over');
            });
            
            item.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('drag-over');
                
                if (this !== draggedItem && draggedItem) {
                    const allItems = [...list.querySelectorAll('.sortable-item')];
                    const draggedIndex = allItems.indexOf(draggedItem);
                    const dropIndex = allItems.indexOf(this);
                    
                    if (draggedIndex < dropIndex) {
                        this.parentNode.insertBefore(draggedItem, this.nextSibling);
                    } else {
                        this.parentNode.insertBefore(draggedItem, this);
                    }
                    
                    updateSortNumbers(list);
                    hasChanges = true;
                }
            });
        });
    }
    
    function updateSortNumbers(list) {
        list.querySelectorAll('.sortable-item').forEach((item, index) => {
            const numberEl = item.querySelector('.sort-number');
            if (numberEl) {
                numberEl.textContent = index + 1;
            }
        });
    }
    
    async function saveSort() {
        const saveBtn = document.getElementById('save-btn');
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>儲存中...';
        
        const items = [];
        
        // 收集首頁主輪播排序
        document.querySelectorAll('#hero-sortable-list .sortable-item').forEach((item, index) => {
            items.push({
                id: parseInt(item.dataset.id),
                sort_order: index + 1
            });
        });
        
        // 收集下方三圖輪播排序
        document.querySelectorAll('#features-sortable-list .sortable-item').forEach((item, index) => {
            items.push({
                id: parseInt(item.dataset.id),
                sort_order: index + 1
            });
        });
        
        try {
            const response = await fetch('<?= url('/admin/banners/update-sort') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': getCsrfToken()
                },
                body: JSON.stringify({ 
                    items: items,
                    csrf_token: getCsrfToken()
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                showToast(result.message, 'success');
                hasChanges = false;
            } else {
                showToast(result.message, 'error');
            }
        } catch (error) {
            showToast('發生錯誤，請稍後再試', 'error');
        }
        
        saveBtn.disabled = false;
        saveBtn.innerHTML = '<i class="fas fa-save mr-2"></i>儲存排序';
    }
    
    // 離開頁面前提醒
    window.addEventListener('beforeunload', function(e) {
        if (hasChanges) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
</script>
