<?php 
$currentPage = 'banners';
use App\Models\Banner;
?>

<!-- Header -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-[#212529]">圖片輪播維護</h2>
        <p class="text-[#6C757D]">管理首頁輪播圖片與影片</p>
    </div>
    <div class="flex space-x-2">
        <a href="<?= url('/admin/banners/sort') ?>" class="bg-[#6C757D] text-white px-4 py-2 rounded-lg hover:bg-[#5a6268] transition">
            <i class="fas fa-sort mr-2"></i>調整排序
        </a>
        <a href="<?= url('/admin/banners/create') ?>" class="bg-[#4A90D9] text-white px-4 py-2 rounded-lg hover:bg-[#357ABD] transition">
            <i class="fas fa-plus mr-2"></i>新增輪播
        </a>
    </div>
</div>

<!-- 輪播位置說明 -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <a href="?position=hero" class="bg-white rounded-lg border-2 <?= ($position ?? '') === 'hero' ? 'border-[#4A90D9]' : 'border-[#E9ECEF]' ?> p-4 hover:border-[#4A90D9] hover:shadow-md transition cursor-pointer block">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-[#4A90D9] bg-opacity-10 rounded-lg flex items-center justify-center mr-4">
                <i class="fas fa-film text-[#4A90D9] text-xl"></i>
            </div>
            <div>
                <h3 class="font-semibold text-[#212529]">首頁主輪播</h3>
                <p class="text-sm text-[#6C757D]">支援圖片或影片，全寬顯示於首頁頂部</p>
            </div>
        </div>
    </a>
    <a href="?position=features" class="bg-white rounded-lg border-2 <?= ($position ?? '') === 'features' ? 'border-[#28A745]' : 'border-[#E9ECEF]' ?> p-4 hover:border-[#28A745] hover:shadow-md transition cursor-pointer block">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-[#28A745] bg-opacity-10 rounded-lg flex items-center justify-center mr-4">
                <i class="fas fa-images text-[#28A745] text-xl"></i>
            </div>
            <div>
                <h3 class="font-semibold text-[#212529]">下方三圖輪播</h3>
                <p class="text-sm text-[#6C757D]">圖片+文字，顯示於首頁特色區塊</p>
            </div>
        </div>
    </a>
</div>

<!-- Search & Filter -->
<div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm mb-6 p-4">
    <form method="GET" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="search" value="<?= htmlspecialchars($search ?? '') ?>" 
                   placeholder="搜尋標題或描述..."
                   class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]">
        </div>
        <div class="w-40">
            <select name="position" class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]">
                <option value="">全部位置</option>
                <option value="hero" <?= ($position ?? '') === 'hero' ? 'selected' : '' ?>>首頁主輪播</option>
                <option value="features" <?= ($position ?? '') === 'features' ? 'selected' : '' ?>>下方三圖輪播</option>
            </select>
        </div>
        <div class="w-32">
            <select name="status" class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]">
                <option value="">全部狀態</option>
                <option value="1" <?= ($status ?? '') === '1' ? 'selected' : '' ?>>顯示</option>
                <option value="0" <?= ($status ?? '') === '0' ? 'selected' : '' ?>>停用</option>
            </select>
        </div>
        <button type="submit" class="bg-[#6C757D] text-white px-4 py-2 rounded-lg hover:bg-[#5a6268]">
            <i class="fas fa-search mr-2"></i>搜尋
        </button>
        <a href="<?= url('/admin/banners') ?>" class="bg-[#F8F9FA] text-[#212529] px-4 py-2 rounded-lg hover:bg-[#E9ECEF] border border-[#DEE2E6]">
            <i class="fas fa-redo mr-2"></i>重設
        </a>
    </form>
</div>

<!-- Banners Table -->
<div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-[#E9ECEF]">
        <thead class="bg-[#F8F9FA]">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-[#212529] uppercase w-16">排序</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-[#212529] uppercase w-28">位置</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-[#212529] uppercase w-32">預覽</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-[#212529] uppercase">標題</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-[#212529] uppercase w-20">類型</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-[#212529] uppercase w-24">狀態</th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-[#212529] uppercase w-32">操作</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-[#E9ECEF]">
            <?php if (empty($banners)): ?>
            <tr>
                <td colspan="7" class="px-6 py-8 text-center text-[#6C757D]">
                    <i class="fas fa-images text-4xl mb-2"></i>
                    <p>沒有輪播資料</p>
                </td>
            </tr>
            <?php else: ?>
            <?php foreach ($banners as $banner): ?>
            <tr class="hover:bg-[#F5F5F5]">
                <td class="px-4 py-3 text-center">
                    <span class="inline-flex items-center justify-center w-8 h-8 bg-[#F8F9FA] rounded-full text-[#6C757D] text-sm font-medium">
                        <?= $banner['sort_order'] ?>
                    </span>
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <?php if (($banner['position'] ?? 'hero') === 'hero'): ?>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-[#4A90D9] bg-opacity-10 text-[#4A90D9]">
                        <i class="fas fa-film mr-1"></i>主輪播
                    </span>
                    <?php else: ?>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-[#28A745] bg-opacity-10 text-[#28A745]">
                        <i class="fas fa-images mr-1"></i>三圖輪播
                    </span>
                    <?php endif; ?>
                </td>
                <td class="px-4 py-3">
                    <?php if (($banner['media_type'] ?? 'image') === 'video' && !empty($banner['video_path'])): ?>
                    <div class="w-24 h-14 bg-[#212529] rounded-lg border border-[#E9ECEF] flex items-center justify-center cursor-pointer hover:opacity-80 transition"
                         onclick="previewVideo('<?= url($banner['video_path']) ?>')">
                        <i class="fas fa-play text-white text-xl"></i>
                    </div>
                    <?php elseif (!empty($banner['image_path'])): ?>
                    <img src="<?= url($banner['image_path']) ?>" alt="" 
                         class="w-24 h-14 object-cover rounded-lg border border-[#E9ECEF] cursor-pointer hover:opacity-80 transition"
                         onclick="previewImage('<?= url($banner['image_path']) ?>')">
                    <?php else: ?>
                    <div class="w-24 h-14 bg-[#F8F9FA] rounded-lg border border-[#E9ECEF] flex items-center justify-center">
                        <i class="fas fa-image text-[#ADB5BD] text-xl"></i>
                    </div>
                    <?php endif; ?>
                </td>
                <td class="px-4 py-3">
                    <div class="text-sm font-medium text-[#212529]"><?= htmlspecialchars($banner['title']) ?></div>
                    <?php if (!empty($banner['description'])): ?>
                    <div class="text-xs text-[#6C757D] truncate max-w-xs"><?= htmlspecialchars($banner['description']) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($banner['link_url'])): ?>
                    <div class="text-xs text-[#4A90D9]">
                        <i class="fas fa-link mr-1"></i>
                        <a href="<?= htmlspecialchars($banner['link_url']) ?>" target="_blank" class="hover:underline truncate inline-block max-w-[200px]">
                            <?= htmlspecialchars($banner['link_url']) ?>
                        </a>
                    </div>
                    <?php endif; ?>
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <?php if (($banner['media_type'] ?? 'image') === 'video'): ?>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-[#6f42c1] bg-opacity-10 text-[#6f42c1]">
                        <i class="fas fa-video mr-1"></i>影片
                    </span>
                    <?php else: ?>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-[#17a2b8] bg-opacity-10 text-[#17a2b8]">
                        <i class="fas fa-image mr-1"></i>圖片
                    </span>
                    <?php endif; ?>
                </td>
                <td class="px-4 py-3">
                    <button onclick="toggleStatus(<?= $banner['id'] ?>)" class="focus:outline-none">
                        <?php if ($banner['status'] == 1): ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#D4EDDA] text-[#155724] cursor-pointer hover:bg-[#c3e6cb]">
                            <i class="fas fa-eye mr-1"></i>顯示
                        </span>
                        <?php else: ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#F8D7DA] text-[#721C24] cursor-pointer hover:bg-[#f5c6cb]">
                            <i class="fas fa-eye-slash mr-1"></i>停用
                        </span>
                        <?php endif; ?>
                    </button>
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                    <a href="<?= url('/admin/banners/edit/' . $banner['id']) ?>" class="text-[#4A90D9] hover:text-[#357ABD] mr-3" title="編輯">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button onclick="deleteBanner(<?= $banner['id'] ?>, '<?= htmlspecialchars(addslashes($banner['title'])) ?>')" 
                            class="text-[#DC3545] hover:text-[#a71d2a]" title="刪除">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Pagination -->
<?php if (!empty($pagination) && $pagination['total_pages'] > 1): ?>
<div class="mt-6 flex justify-between items-center">
    <div class="text-sm text-[#6C757D]">
        共 <?= $pagination['total'] ?> 筆資料
    </div>
    <nav class="flex space-x-2">
        <?php if ($pagination['current_page'] > 1): ?>
        <a href="?page=<?= $pagination['current_page'] - 1 ?>&search=<?= urlencode($search ?? '') ?>&position=<?= urlencode($position ?? '') ?>&status=<?= urlencode($status ?? '') ?>" 
           class="px-3 py-2 bg-white border border-[#DEE2E6] rounded-lg hover:bg-[#F8F9FA] text-[#212529]">
            <i class="fas fa-chevron-left"></i>
        </a>
        <?php endif; ?>
        
        <?php for ($i = max(1, $pagination['current_page'] - 2); $i <= min($pagination['total_pages'], $pagination['current_page'] + 2); $i++): ?>
        <a href="?page=<?= $i ?>&search=<?= urlencode($search ?? '') ?>&position=<?= urlencode($position ?? '') ?>&status=<?= urlencode($status ?? '') ?>" 
           class="px-3 py-2 border rounded-lg <?= $i == $pagination['current_page'] ? 'bg-[#4A90D9] text-white border-[#4A90D9]' : 'bg-white border-[#DEE2E6] hover:bg-[#F8F9FA] text-[#212529]' ?>">
            <?= $i ?>
        </a>
        <?php endfor; ?>
        
        <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
        <a href="?page=<?= $pagination['current_page'] + 1 ?>&search=<?= urlencode($search ?? '') ?>&position=<?= urlencode($position ?? '') ?>&status=<?= urlencode($status ?? '') ?>" 
           class="px-3 py-2 bg-white border border-[#DEE2E6] rounded-lg hover:bg-[#F8F9FA] text-[#212529]">
            <i class="fas fa-chevron-right"></i>
        </a>
        <?php endif; ?>
    </nav>
</div>
<?php endif; ?>

<!-- Image Preview Modal -->
<div id="preview-modal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black bg-opacity-75" onclick="closePreview()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="relative max-w-4xl max-h-[90vh]">
            <button onclick="closePreview()" class="absolute -top-10 right-0 text-white text-2xl hover:text-gray-300">
                <i class="fas fa-times"></i>
            </button>
            <img id="preview-image" src="" alt="" class="max-w-full max-h-[85vh] rounded-lg shadow-xl">
        </div>
    </div>
</div>

<!-- Video Preview Modal -->
<div id="video-preview-modal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black bg-opacity-75" onclick="closeVideoPreview()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="relative max-w-4xl max-h-[90vh]">
            <button onclick="closeVideoPreview()" class="absolute -top-10 right-0 text-white text-2xl hover:text-gray-300">
                <i class="fas fa-times"></i>
            </button>
            <video id="preview-video" src="" controls class="max-w-full max-h-[85vh] rounded-lg shadow-xl"></video>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="delete-modal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black bg-opacity-50" onclick="closeDeleteModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 border border-[#E9ECEF]">
            <div class="text-center">
                <div class="text-[#DC3545] text-5xl mb-4">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3 class="text-lg font-semibold text-[#212529] mb-2">確定要刪除嗎？</h3>
                <p class="text-[#6C757D] mb-6">您確定要刪除輪播 <span id="delete-banner-name" class="font-semibold text-[#212529]"></span> 嗎？此操作無法復原。</p>
                <div class="flex space-x-4">
                    <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2 bg-[#F8F9FA] text-[#212529] rounded-lg hover:bg-[#E9ECEF] border border-[#DEE2E6]">
                        取消
                    </button>
                    <button onclick="confirmDelete()" class="flex-1 px-4 py-2 bg-[#DC3545] text-white rounded-lg hover:bg-[#a71d2a]">
                        確定刪除
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let deleteBannerId = null;
    
    // 圖片預覽
    function previewImage(src) {
        document.getElementById('preview-image').src = src;
        document.getElementById('preview-modal').classList.remove('hidden');
    }
    
    function closePreview() {
        document.getElementById('preview-modal').classList.add('hidden');
    }
    
    // 影片預覽
    function previewVideo(src) {
        const video = document.getElementById('preview-video');
        video.src = src;
        document.getElementById('video-preview-modal').classList.remove('hidden');
        video.play();
    }
    
    function closeVideoPreview() {
        const video = document.getElementById('preview-video');
        video.pause();
        video.src = '';
        document.getElementById('video-preview-modal').classList.add('hidden');
    }
    
    // 切換狀態
    async function toggleStatus(id) {
        try {
            const response = await fetch(`<?= url('/admin/banners/toggle-status/') ?>${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': getCsrfToken()
                },
                body: JSON.stringify({ csrf_token: getCsrfToken() })
            });
            
            const result = await response.json();
            
            if (result.success) {
                showToast(result.message, 'success');
                setTimeout(() => location.reload(), 500);
            } else {
                showToast(result.message, 'error');
            }
        } catch (error) {
            showToast('發生錯誤，請稍後再試', 'error');
        }
    }
    
    // 刪除
    function deleteBanner(id, name) {
        deleteBannerId = id;
        document.getElementById('delete-banner-name').textContent = name;
        document.getElementById('delete-modal').classList.remove('hidden');
    }
    
    function closeDeleteModal() {
        document.getElementById('delete-modal').classList.add('hidden');
        deleteBannerId = null;
    }
    
    async function confirmDelete() {
        if (!deleteBannerId) return;
        
        try {
            const response = await fetch(`<?= url('/admin/banners/delete/') ?>${deleteBannerId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': getCsrfToken()
                },
                body: JSON.stringify({ csrf_token: getCsrfToken() })
            });
            
            const result = await response.json();
            
            if (result.success) {
                showToast(result.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(result.message, 'error');
            }
        } catch (error) {
            showToast('發生錯誤，請稍後再試', 'error');
        }
        
        closeDeleteModal();
    }
    
    // ESC 關閉預覽
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closePreview();
            closeVideoPreview();
            closeDeleteModal();
        }
    });
</script>
