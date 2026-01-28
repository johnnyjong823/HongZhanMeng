<?php $currentPage = 'cibes'; ?>

<!-- Header -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-[#212529]">Cibes 品牌維護</h2>
        <p class="text-[#6C757D]">管理 Cibes 品牌資料</p>
    </div>
    <div class="flex space-x-2">
        <a href="<?= url('/admin/cibes/create') ?>" class="bg-[#4A90D9] text-white px-4 py-2 rounded-lg hover:bg-[#357ABD] transition">
            <i class="fas fa-plus mr-2"></i>新增品牌
        </a>
    </div>
</div>

<!-- Search & Filter -->
<div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm mb-6 p-4">
    <form method="GET" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="search" value="<?= htmlspecialchars($search ?? '') ?>" 
                   placeholder="搜尋品牌名稱或內容..."
                   class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]">
        </div>
        <div class="w-40">
            <select name="category" class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]">
                <option value="">全部類別</option>
                <?php foreach ($categories as $cat): ?>
                <option value="<?= htmlspecialchars($cat['code_id']) ?>" <?= ($category ?? '') === $cat['code_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['code_name']) ?>
                </option>
                <?php endforeach; ?>
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
        <a href="<?= url('/admin/cibes') ?>" class="bg-[#F8F9FA] text-[#212529] px-4 py-2 rounded-lg hover:bg-[#E9ECEF] border border-[#DEE2E6]">
            <i class="fas fa-redo mr-2"></i>重設
        </a>
    </form>
</div>

<!-- Items Table -->
<div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-[#E9ECEF]">
        <thead class="bg-[#F8F9FA]">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-[#212529] uppercase w-16">排序</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-[#212529] uppercase w-24">圖片</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-[#212529] uppercase">品牌名稱</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-[#212529] uppercase w-32">類別</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-[#212529] uppercase w-40">更新時間</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-[#212529] uppercase w-24">狀態</th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-[#212529] uppercase w-32">操作</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-[#E9ECEF]">
            <?php if (empty($items)): ?>
            <tr>
                <td colspan="7" class="px-6 py-8 text-center text-[#6C757D]">
                    <i class="fas fa-box-open text-4xl mb-2"></i>
                    <p>沒有品牌資料</p>
                </td>
            </tr>
            <?php else: ?>
            <?php 
            // 建立類別對照表
            $categoryMap = [];
            foreach ($categories as $cat) {
                $categoryMap[$cat['code_id']] = $cat['code_name'];
            }
            ?>
            <?php foreach ($items as $item): ?>
            <tr class="hover:bg-[#F5F5F5]">
                <td class="px-4 py-3 text-center">
                    <span class="inline-flex items-center justify-center w-8 h-8 bg-[#F8F9FA] rounded-full text-[#6C757D] text-sm font-medium">
                        <?= $item['sort_order'] ?>
                    </span>
                </td>
                <td class="px-4 py-3">
                    <?php if (!empty($item['image_path'])): ?>
                    <img src="<?= url($item['image_path']) ?>" alt="" 
                         class="w-16 h-16 object-cover rounded-lg border border-[#E9ECEF] cursor-pointer hover:opacity-80 transition"
                         onclick="previewImage('<?= url($item['image_path']) ?>')">
                    <?php else: ?>
                    <div class="w-16 h-16 bg-[#F8F9FA] rounded-lg border border-[#E9ECEF] flex items-center justify-center">
                        <i class="fas fa-image text-[#ADB5BD] text-xl"></i>
                    </div>
                    <?php endif; ?>
                </td>
                <td class="px-4 py-3">
                    <div class="text-sm font-medium text-[#212529]"><?= htmlspecialchars($item['name']) ?></div>
                    <?php if (!empty($item['content'])): ?>
                    <?php $contentText = strip_tags($item['content']); ?>
                    <div class="text-xs text-[#6C757D] truncate max-w-xs"><?= htmlspecialchars(function_exists('mb_substr') ? mb_substr($contentText, 0, 50) : substr($contentText, 0, 50)) ?>...</div>
                    <?php endif; ?>
                </td>
                <td class="px-4 py-3 text-sm text-[#6C757D]">
                    <?php if (!empty($item['category']) && isset($categoryMap[$item['category']])): ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#E9ECEF] text-[#212529]">
                        <?= htmlspecialchars($categoryMap[$item['category']]) ?>
                    </span>
                    <?php else: ?>
                    <span class="text-[#ADB5BD]">-</span>
                    <?php endif; ?>
                </td>
                <td class="px-4 py-3 text-sm text-[#6C757D]">
                    <?= date('Y/m/d H:i', strtotime($item['updated_at'])) ?>
                </td>
                <td class="px-4 py-3">
                    <button onclick="toggleStatus(<?= $item['id'] ?>)" class="focus:outline-none">
                        <?php if ($item['status'] == 1): ?>
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
                    <a href="<?= url('/admin/cibes/edit/' . $item['id']) ?>" class="text-[#4A90D9] hover:text-[#357ABD] mr-3" title="編輯">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button onclick="deleteItem(<?= $item['id'] ?>, '<?= htmlspecialchars(addslashes($item['name'])) ?>')" 
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
        <a href="?page=<?= $pagination['current_page'] - 1 ?>&search=<?= urlencode($search ?? '') ?>&status=<?= urlencode($status ?? '') ?>&category=<?= urlencode($category ?? '') ?>" 
           class="px-3 py-2 bg-white border border-[#DEE2E6] rounded-lg hover:bg-[#F8F9FA] text-[#212529]">
            <i class="fas fa-chevron-left"></i>
        </a>
        <?php endif; ?>
        
        <?php for ($i = max(1, $pagination['current_page'] - 2); $i <= min($pagination['total_pages'], $pagination['current_page'] + 2); $i++): ?>
        <a href="?page=<?= $i ?>&search=<?= urlencode($search ?? '') ?>&status=<?= urlencode($status ?? '') ?>&category=<?= urlencode($category ?? '') ?>" 
           class="px-3 py-2 border rounded-lg <?= $i == $pagination['current_page'] ? 'bg-[#4A90D9] text-white border-[#4A90D9]' : 'bg-white border-[#DEE2E6] hover:bg-[#F8F9FA] text-[#212529]' ?>">
            <?= $i ?>
        </a>
        <?php endfor; ?>
        
        <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
        <a href="?page=<?= $pagination['current_page'] + 1 ?>&search=<?= urlencode($search ?? '') ?>&status=<?= urlencode($status ?? '') ?>&category=<?= urlencode($category ?? '') ?>" 
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
                <p class="text-[#6C757D] mb-6">您確定要刪除品牌 <span id="delete-item-name" class="font-semibold text-[#212529]"></span> 嗎？此操作無法復原。</p>
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
    let deleteItemId = null;
    
    // 圖片預覽
    function previewImage(src) {
        document.getElementById('preview-image').src = src;
        document.getElementById('preview-modal').classList.remove('hidden');
    }
    
    function closePreview() {
        document.getElementById('preview-modal').classList.add('hidden');
    }
    
    // 切換狀態
    async function toggleStatus(id) {
        try {
            const response = await fetch(`<?= url('/admin/cibes/toggle-status/') ?>${id}`, {
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
    function deleteItem(id, name) {
        deleteItemId = id;
        document.getElementById('delete-item-name').textContent = name;
        document.getElementById('delete-modal').classList.remove('hidden');
    }
    
    function closeDeleteModal() {
        document.getElementById('delete-modal').classList.add('hidden');
        deleteItemId = null;
    }
    
    async function confirmDelete() {
        if (!deleteItemId) return;
        
        try {
            const response = await fetch(`<?= url('/admin/cibes/delete/') ?>${deleteItemId}`, {
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
            closeDeleteModal();
        }
    });
</script>
