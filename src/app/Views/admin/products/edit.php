<?php 
$currentPage = 'products';
$activeTab = $activeTab ?? 'basic';
?>

<!-- Header -->
<div class="mb-6">
    <div class="flex items-center space-x-2 text-sm text-[#6C757D] mb-2">
        <a href="<?= url('/admin/products') ?>" class="hover:text-[#4A90D9]">產品維護</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-[#212529]"><?= htmlspecialchars($product['product_name']) ?></span>
    </div>
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-[#212529]"><?= htmlspecialchars($product['product_name']) ?></h2>
        <a href="<?= url('/admin/products') ?>" class="text-[#6C757D] hover:text-[#212529]">
            <i class="fas fa-arrow-left mr-2"></i>返回列表
        </a>
    </div>
</div>

<!-- Tabs Navigation -->
<div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm mb-6">
    <div class="border-b border-[#E9ECEF]">
        <nav class="flex -mb-px">
            <button onclick="switchTab('basic')" id="tab-basic"
                    class="tab-btn px-6 py-4 text-sm font-medium border-b-2 <?= $activeTab === 'basic' ? 'text-[#4A90D9] border-[#4A90D9]' : 'text-[#6C757D] border-transparent hover:text-[#212529] hover:border-[#DEE2E6]' ?>">
                <i class="fas fa-info-circle mr-2"></i>基本資訊
            </button>
            <button onclick="switchTab('details')" id="tab-details"
                    class="tab-btn px-6 py-4 text-sm font-medium border-b-2 <?= $activeTab === 'details' ? 'text-[#4A90D9] border-[#4A90D9]' : 'text-[#6C757D] border-transparent hover:text-[#212529] hover:border-[#DEE2E6]' ?>">
                <i class="fas fa-list-ul mr-2"></i>產品明細
                <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-[#E9ECEF] text-[#6C757D]"><?= count($product['details'] ?? []) ?></span>
            </button>
            <button onclick="switchTab('faqs')" id="tab-faqs"
                    class="tab-btn px-6 py-4 text-sm font-medium border-b-2 <?= $activeTab === 'faqs' ? 'text-[#4A90D9] border-[#4A90D9]' : 'text-[#6C757D] border-transparent hover:text-[#212529] hover:border-[#DEE2E6]' ?>">
                <i class="fas fa-question-circle mr-2"></i>Q&A
                <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-[#E9ECEF] text-[#6C757D]"><?= count($product['faqs'] ?? []) ?></span>
            </button>
            <button onclick="switchTab('manuals')" id="tab-manuals"
                    class="tab-btn px-6 py-4 text-sm font-medium border-b-2 <?= $activeTab === 'manuals' ? 'text-[#4A90D9] border-[#4A90D9]' : 'text-[#6C757D] border-transparent hover:text-[#212529] hover:border-[#DEE2E6]' ?>">
                <i class="fas fa-file-pdf mr-2"></i>技術手冊
                <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-[#E9ECEF] text-[#6C757D]"><?= count($product['manuals'] ?? []) ?></span>
            </button>
        </nav>
    </div>
</div>

<!-- Tab Contents -->
<div id="tab-content">
    <!-- Basic Info Tab -->
    <div id="content-basic" class="tab-content <?= $activeTab !== 'basic' ? 'hidden' : '' ?>">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- 主要表單 -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm p-6">
                    <form id="basic-form">
                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                        
                        <div class="space-y-6">
                            <!-- 產品名稱 -->
                            <div>
                                <label for="product_name" class="block text-sm font-medium text-[#212529] mb-1">
                                    產品名稱 <span class="text-[#DC3545]">*</span>
                                </label>
                                <input type="text" id="product_name" name="product_name" required
                                       value="<?= htmlspecialchars($product['product_name'] ?? '') ?>"
                                       class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]">
                            </div>
                            
                            <!-- 簡短介紹 -->
                            <div>
                                <label for="short_description" class="block text-sm font-medium text-[#212529] mb-1">簡短介紹</label>
                                <input type="text" id="short_description" name="short_description"
                                       value="<?= htmlspecialchars($product['short_description'] ?? '') ?>"
                                       class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                                       placeholder="顯示在產品卡片上的簡短說明">
                            </div>
                            
                            <!-- 詳細介紹 -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-[#212529] mb-1">詳細介紹</label>
                                <textarea id="description" name="description" rows="6"
                                          class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                                          placeholder="產品的詳細介紹內容"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                            </div>
                            
                            <!-- 狀態 -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-[#212529] mb-1">狀態</label>
                                <select id="status" name="status"
                                        class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]">
                                    <option value="1" <?= ($product['status'] ?? 1) == 1 ? 'selected' : '' ?>>顯示</option>
                                    <option value="0" <?= ($product['status'] ?? 1) == 0 ? 'selected' : '' ?>>停用</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mt-6 pt-6 border-t border-[#E9ECEF]">
                            <button type="submit" class="px-6 py-2 bg-[#4A90D9] text-white rounded-lg hover:bg-[#357ABD]">
                                <i class="fas fa-save mr-2"></i>儲存變更
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- 側邊欄：產品圖片 -->
            <div>
                <div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-[#212529] mb-4">
                        <i class="fas fa-images text-[#4A90D9] mr-2"></i>產品圖片
                    </h3>
                    
                    <!-- 上傳區域 -->
                    <div class="mb-4">
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-[#DEE2E6] rounded-lg cursor-pointer hover:bg-[#F8F9FA] transition">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <i class="fas fa-cloud-upload-alt text-3xl text-[#ADB5BD] mb-2"></i>
                                <p class="text-sm text-[#6C757D]">點擊上傳圖片</p>
                                <p class="text-xs text-[#ADB5BD]">JPG, PNG, WebP (最大 5MB)</p>
                            </div>
                            <input type="file" id="image-upload" class="hidden" accept="image/*" multiple>
                        </label>
                    </div>
                    
                    <!-- 圖片列表 -->
                    <div id="image-list" class="grid grid-cols-2 gap-3">
                        <?php foreach ($product['images'] ?? [] as $image): ?>
                        <div class="relative group" data-image-id="<?= $image['id'] ?>">
                            <img src="<?= url($image['image_path']) ?>" alt="" 
                                 class="w-full h-24 object-cover rounded-lg border border-[#E9ECEF]">
                            <?php if ($image['image_type'] === 'main'): ?>
                            <span class="absolute top-1 left-1 bg-[#4A90D9] text-white text-xs px-2 py-0.5 rounded">主圖</span>
                            <?php endif; ?>
                            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition rounded-lg flex items-center justify-center space-x-2">
                                <?php if ($image['image_type'] !== 'main'): ?>
                                <button onclick="setMainImage(<?= $image['id'] ?>)" 
                                        class="p-2 bg-white rounded-full text-[#4A90D9] hover:bg-[#4A90D9] hover:text-white transition" title="設為主圖">
                                    <i class="fas fa-star text-sm"></i>
                                </button>
                                <?php endif; ?>
                                <button onclick="deleteImage(<?= $image['id'] ?>)" 
                                        class="p-2 bg-white rounded-full text-[#DC3545] hover:bg-[#DC3545] hover:text-white transition" title="刪除">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <?php if (empty($product['images'])): ?>
                    <p id="no-images-text" class="text-center text-[#ADB5BD] text-sm py-4">尚未上傳圖片</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Details Tab -->
    <div id="content-details" class="tab-content <?= $activeTab !== 'details' ? 'hidden' : '' ?>">
        <div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm">
            <div class="p-4 border-b border-[#E9ECEF] flex justify-between items-center">
                <h3 class="text-lg font-semibold text-[#212529]">
                    <i class="fas fa-list-ul text-[#4A90D9] mr-2"></i>產品明細
                </h3>
                <button onclick="openDetailModal()" class="px-4 py-2 bg-[#4A90D9] text-white rounded-lg hover:bg-[#357ABD] text-sm">
                    <i class="fas fa-plus mr-2"></i>新增明細
                </button>
            </div>
            
            <div id="details-list" class="divide-y divide-[#E9ECEF]">
                <?php if (empty($product['details'])): ?>
                <div class="p-8 text-center text-[#6C757D]">
                    <i class="fas fa-list text-4xl text-[#DEE2E6] mb-3"></i>
                    <p>尚未新增產品明細</p>
                </div>
                <?php else: ?>
                <?php foreach ($product['details'] as $detail): ?>
                <div class="p-4 hover:bg-[#F8F9FA]" data-detail-id="<?= $detail['id'] ?>">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3">
                                <h4 class="font-medium text-[#212529]"><?= htmlspecialchars($detail['title']) ?></h4>
                                <?php if ($detail['status'] == 0): ?>
                                <span class="px-2 py-0.5 text-xs rounded bg-[#F8D7DA] text-[#721C24]">停用</span>
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($detail['content'])): ?>
                            <p class="text-sm text-[#6C757D] mt-1 line-clamp-2"><?= htmlspecialchars($detail['content']) ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="flex items-center space-x-2 ml-4">
                            <button onclick="editDetail(<?= htmlspecialchars(json_encode($detail)) ?>)" 
                                    class="p-2 text-[#4A90D9] hover:bg-[#E9ECEF] rounded" title="編輯">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteDetail(<?= $detail['id'] ?>)" 
                                    class="p-2 text-[#DC3545] hover:bg-[#E9ECEF] rounded" title="刪除">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- FAQs Tab -->
    <div id="content-faqs" class="tab-content <?= $activeTab !== 'faqs' ? 'hidden' : '' ?>">
        <div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm">
            <div class="p-4 border-b border-[#E9ECEF] flex justify-between items-center">
                <h3 class="text-lg font-semibold text-[#212529]">
                    <i class="fas fa-question-circle text-[#28A745] mr-2"></i>Q&A 問答
                </h3>
                <button onclick="openFaqModal()" class="px-4 py-2 bg-[#28A745] text-white rounded-lg hover:bg-[#218838] text-sm">
                    <i class="fas fa-plus mr-2"></i>新增 Q&A
                </button>
            </div>
            
            <div id="faqs-list" class="divide-y divide-[#E9ECEF]">
                <?php if (empty($product['faqs'])): ?>
                <div class="p-8 text-center text-[#6C757D]">
                    <i class="fas fa-question-circle text-4xl text-[#DEE2E6] mb-3"></i>
                    <p>尚未新增 Q&A</p>
                </div>
                <?php else: ?>
                <?php foreach ($product['faqs'] as $faq): ?>
                <div class="p-4 hover:bg-[#F8F9FA]" data-faq-id="<?= $faq['id'] ?>">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3">
                                <span class="flex-shrink-0 w-6 h-6 bg-[#4A90D9] text-white rounded-full flex items-center justify-center text-xs font-bold">Q</span>
                                <h4 class="font-medium text-[#212529]"><?= htmlspecialchars($faq['question']) ?></h4>
                                <?php if ($faq['status'] == 0): ?>
                                <span class="px-2 py-0.5 text-xs rounded bg-[#F8D7DA] text-[#721C24]">停用</span>
                                <?php endif; ?>
                            </div>
                            <div class="flex items-start space-x-3 mt-2 ml-9">
                                <span class="flex-shrink-0 w-6 h-6 bg-[#28A745] text-white rounded-full flex items-center justify-center text-xs font-bold">A</span>
                                <p class="text-sm text-[#6C757D] line-clamp-2"><?= htmlspecialchars($faq['answer']) ?></p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 ml-4">
                            <button onclick="editFaq(<?= htmlspecialchars(json_encode($faq)) ?>)" 
                                    class="p-2 text-[#4A90D9] hover:bg-[#E9ECEF] rounded" title="編輯">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteFaq(<?= $faq['id'] ?>)" 
                                    class="p-2 text-[#DC3545] hover:bg-[#E9ECEF] rounded" title="刪除">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Manuals Tab -->
    <div id="content-manuals" class="tab-content <?= $activeTab !== 'manuals' ? 'hidden' : '' ?>">
        <div class="bg-white rounded-lg border border-[#E9ECEF] shadow-sm">
            <div class="p-4 border-b border-[#E9ECEF] flex justify-between items-center">
                <h3 class="text-lg font-semibold text-[#212529]">
                    <i class="fas fa-file-pdf text-[#DC3545] mr-2"></i>技術手冊
                </h3>
                <button onclick="openManualModal()" class="px-4 py-2 bg-[#DC3545] text-white rounded-lg hover:bg-[#c82333] text-sm">
                    <i class="fas fa-upload mr-2"></i>上傳手冊
                </button>
            </div>
            
            <div id="manuals-list" class="divide-y divide-[#E9ECEF]">
                <?php if (empty($product['manuals'])): ?>
                <div class="p-8 text-center text-[#6C757D]">
                    <i class="fas fa-file-pdf text-4xl text-[#DEE2E6] mb-3"></i>
                    <p>尚未上傳技術手冊</p>
                </div>
                <?php else: ?>
                <?php foreach ($product['manuals'] as $manual): ?>
                <?php
                $fileIcon = \App\Models\ProductManual::getFileIcon($manual['file_type'] ?? 'pdf');
                $fileSize = \App\Models\ProductManual::formatFileSize($manual['file_size'] ?? 0);
                ?>
                <div class="p-4 hover:bg-[#F8F9FA]" data-manual-id="<?= $manual['id'] ?>">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-[#F8D7DA] rounded-lg flex items-center justify-center">
                                <i class="fas <?= $fileIcon ?> text-[#DC3545] text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-[#212529]"><?= htmlspecialchars($manual['title']) ?></h4>
                                <p class="text-sm text-[#6C757D]">
                                    <?= htmlspecialchars($manual['filename']) ?> · <?= $fileSize ?>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="<?= htmlspecialchars($manual['file_path']) ?>" target="_blank"
                               class="p-2 text-[#4A90D9] hover:bg-[#E9ECEF] rounded" title="下載">
                                <i class="fas fa-download"></i>
                            </a>
                            <button onclick="editManual(<?= htmlspecialchars(json_encode($manual)) ?>)" 
                                    class="p-2 text-[#6C757D] hover:bg-[#E9ECEF] rounded" title="編輯標題">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteManual(<?= $manual['id'] ?>)" 
                                    class="p-2 text-[#DC3545] hover:bg-[#E9ECEF] rounded" title="刪除">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div id="detail-modal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black bg-opacity-50" onclick="closeDetailModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full border border-[#E9ECEF]">
            <div class="p-4 border-b border-[#E9ECEF] flex justify-between items-center">
                <h3 id="detail-modal-title" class="text-lg font-semibold text-[#212529]">新增產品明細</h3>
                <button onclick="closeDetailModal()" class="text-[#6C757D] hover:text-[#212529]">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="detail-form" class="p-4 space-y-4">
                <input type="hidden" id="detail-id" name="detail_id" value="">
                <div>
                    <label class="block text-sm font-medium text-[#212529] mb-1">標題 <span class="text-[#DC3545]">*</span></label>
                    <input type="text" id="detail-title" name="title" required
                           class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#212529] mb-1">內容</label>
                    <textarea id="detail-content" name="content" rows="4"
                              class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-[#212529] mb-1">排序</label>
                        <input type="number" id="detail-sort" name="sort_order" value="0"
                               class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#212529] mb-1">狀態</label>
                        <select id="detail-status" name="status"
                                class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]">
                            <option value="1">顯示</option>
                            <option value="0">停用</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end space-x-4 pt-4 border-t border-[#E9ECEF]">
                    <button type="button" onclick="closeDetailModal()" class="px-4 py-2 bg-[#F8F9FA] text-[#212529] rounded-lg hover:bg-[#E9ECEF] border border-[#DEE2E6]">
                        取消
                    </button>
                    <button type="submit" class="px-4 py-2 bg-[#4A90D9] text-white rounded-lg hover:bg-[#357ABD]">
                        儲存
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- FAQ Modal -->
<div id="faq-modal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black bg-opacity-50" onclick="closeFaqModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full border border-[#E9ECEF]">
            <div class="p-4 border-b border-[#E9ECEF] flex justify-between items-center">
                <h3 id="faq-modal-title" class="text-lg font-semibold text-[#212529]">新增 Q&A</h3>
                <button onclick="closeFaqModal()" class="text-[#6C757D] hover:text-[#212529]">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="faq-form" class="p-4 space-y-4">
                <input type="hidden" id="faq-id" name="faq_id" value="">
                <div>
                    <label class="block text-sm font-medium text-[#212529] mb-1">問題 <span class="text-[#DC3545]">*</span></label>
                    <textarea id="faq-question" name="question" rows="2" required
                              class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#212529] mb-1">回答 <span class="text-[#DC3545]">*</span></label>
                    <textarea id="faq-answer" name="answer" rows="4" required
                              class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-[#212529] mb-1">排序</label>
                        <input type="number" id="faq-sort" name="sort_order" value="0"
                               class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#212529] mb-1">狀態</label>
                        <select id="faq-status" name="status"
                                class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]">
                            <option value="1">顯示</option>
                            <option value="0">停用</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end space-x-4 pt-4 border-t border-[#E9ECEF]">
                    <button type="button" onclick="closeFaqModal()" class="px-4 py-2 bg-[#F8F9FA] text-[#212529] rounded-lg hover:bg-[#E9ECEF] border border-[#DEE2E6]">
                        取消
                    </button>
                    <button type="submit" class="px-4 py-2 bg-[#28A745] text-white rounded-lg hover:bg-[#218838]">
                        儲存
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Manual Modal -->
<div id="manual-modal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black bg-opacity-50" onclick="closeManualModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full border border-[#E9ECEF]">
            <div class="p-4 border-b border-[#E9ECEF] flex justify-between items-center">
                <h3 id="manual-modal-title" class="text-lg font-semibold text-[#212529]">上傳技術手冊</h3>
                <button onclick="closeManualModal()" class="text-[#6C757D] hover:text-[#212529]">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="manual-form" class="p-4 space-y-4" enctype="multipart/form-data">
                <input type="hidden" id="manual-id" name="manual_id" value="">
                <div>
                    <label class="block text-sm font-medium text-[#212529] mb-1">手冊名稱</label>
                    <input type="text" id="manual-title" name="title"
                           class="w-full px-4 py-2 border border-[#DEE2E6] rounded-lg focus:ring-2 focus:ring-[#4A90D9] focus:border-[#4A90D9] text-[#212529]"
                           placeholder="留空則使用檔案名稱">
                </div>
                <div id="manual-file-upload">
                    <label class="block text-sm font-medium text-[#212529] mb-1">選擇檔案 <span class="text-[#DC3545]">*</span></label>
                    <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-[#DEE2E6] rounded-lg cursor-pointer hover:bg-[#F8F9FA] transition">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <i class="fas fa-file-upload text-3xl text-[#ADB5BD] mb-2"></i>
                            <p class="text-sm text-[#6C757D]" id="manual-file-name">點擊選擇檔案</p>
                            <p class="text-xs text-[#ADB5BD]">PDF, Word, Excel, PowerPoint (最大 50MB)</p>
                        </div>
                        <input type="file" id="manual-file" name="file" class="hidden" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                    </label>
                </div>
                <div class="flex justify-end space-x-4 pt-4 border-t border-[#E9ECEF]">
                    <button type="button" onclick="closeManualModal()" class="px-4 py-2 bg-[#F8F9FA] text-[#212529] rounded-lg hover:bg-[#E9ECEF] border border-[#DEE2E6]">
                        取消
                    </button>
                    <button type="submit" class="px-4 py-2 bg-[#DC3545] text-white rounded-lg hover:bg-[#c82333]">
                        上傳
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<script>
    const productId = <?= $product['id'] ?>;
    
    // ==================== Tab 切換 ====================
    function switchTab(tab) {
        // 隱藏所有內容
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        // 重設所有 Tab 按鈕樣式
        document.querySelectorAll('.tab-btn').forEach(el => {
            el.classList.remove('text-[#4A90D9]', 'border-[#4A90D9]');
            el.classList.add('text-[#6C757D]', 'border-transparent');
        });
        
        // 顯示選中的內容
        document.getElementById('content-' + tab).classList.remove('hidden');
        // 設定選中的 Tab 樣式
        const tabBtn = document.getElementById('tab-' + tab);
        tabBtn.classList.remove('text-[#6C757D]', 'border-transparent');
        tabBtn.classList.add('text-[#4A90D9]', 'border-[#4A90D9]');
        
        // 更新 URL
        history.replaceState(null, '', `?tab=${tab}`);
    }
    
    // ==================== 基本資訊 ====================
    document.getElementById('basic-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = {
            csrf_token: formData.get('csrf_token'),
            product_name: formData.get('product_name'),
            short_description: formData.get('short_description'),
            description: formData.get('description'),
            status: formData.get('status')
        };
        
        try {
            const response = await fetch(`<?= url('/admin/products/update/') ?>${productId}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const result = await response.json();
            
            if (result.success) {
                showToast(result.message, 'success');
            } else {
                showToast(result.message, 'error');
            }
        } catch (error) {
            showToast('發生錯誤，請稍後再試', 'error');
        }
    });
    
    // ==================== 圖片管理 ====================
    document.getElementById('image-upload').addEventListener('change', async function(e) {
        const files = e.target.files;
        if (!files.length) return;
        
        for (let file of files) {
            const formData = new FormData();
            formData.append('image', file);
            formData.append('csrf_token', getCsrfToken());
            
            try {
                const response = await fetch(`<?= url('/admin/products/upload-image/') ?>${productId}`, {
                    method: 'POST',
                    headers: { 'X-CSRF-Token': getCsrfToken() },
                    body: formData
                });
                const result = await response.json();
                
                if (result.success) {
                    showToast(result.message, 'success');
                    addImageToList(result.image);
                } else {
                    showToast(result.message, 'error');
                }
            } catch (error) {
                showToast('圖片上傳失敗', 'error');
            }
        }
        e.target.value = '';
    });
    
    function addImageToList(image) {
        const noImagesText = document.getElementById('no-images-text');
        if (noImagesText) noImagesText.remove();
        
        const imageList = document.getElementById('image-list');
        const div = document.createElement('div');
        div.className = 'relative group';
        div.dataset.imageId = image.id;
        
        const isMain = image.type === 'main';
        div.innerHTML = `
            <img src="${image.path}" alt="" class="w-full h-24 object-cover rounded-lg border border-[#E9ECEF]">
            ${isMain ? '<span class="absolute top-1 left-1 bg-[#4A90D9] text-white text-xs px-2 py-0.5 rounded">主圖</span>' : ''}
            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition rounded-lg flex items-center justify-center space-x-2">
                ${!isMain ? `<button onclick="setMainImage(${image.id})" class="p-2 bg-white rounded-full text-[#4A90D9] hover:bg-[#4A90D9] hover:text-white transition" title="設為主圖"><i class="fas fa-star text-sm"></i></button>` : ''}
                <button onclick="deleteImage(${image.id})" class="p-2 bg-white rounded-full text-[#DC3545] hover:bg-[#DC3545] hover:text-white transition" title="刪除"><i class="fas fa-trash text-sm"></i></button>
            </div>
        `;
        imageList.appendChild(div);
    }
    
    async function setMainImage(imageId) {
        try {
            const response = await fetch(`<?= url('/admin/products/set-main-image/') ?>${productId}/${imageId}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': getCsrfToken() },
                body: JSON.stringify({ csrf_token: getCsrfToken() })
            });
            const result = await response.json();
            if (result.success) {
                showToast(result.message, 'success');
                location.reload();
            } else {
                showToast(result.message, 'error');
            }
        } catch (error) {
            showToast('操作失敗', 'error');
        }
    }
    
    async function deleteImage(imageId) {
        if (!confirm('確定要刪除這張圖片嗎？')) return;
        
        try {
            const response = await fetch(`<?= url('/admin/products/delete-image/') ?>${productId}/${imageId}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': getCsrfToken() },
                body: JSON.stringify({ csrf_token: getCsrfToken() })
            });
            const result = await response.json();
            if (result.success) {
                showToast(result.message, 'success');
                document.querySelector(`[data-image-id="${imageId}"]`).remove();
            } else {
                showToast(result.message, 'error');
            }
        } catch (error) {
            showToast('刪除失敗', 'error');
        }
    }
    
    // ==================== 產品明細 ====================
    function openDetailModal(detail = null) {
        document.getElementById('detail-modal-title').textContent = detail ? '編輯產品明細' : '新增產品明細';
        document.getElementById('detail-id').value = detail?.id || '';
        document.getElementById('detail-title').value = detail?.title || '';
        document.getElementById('detail-content').value = detail?.content || '';
        document.getElementById('detail-sort').value = detail?.sort_order || 0;
        document.getElementById('detail-status').value = detail?.status ?? 1;
        document.getElementById('detail-modal').classList.remove('hidden');
    }
    
    function closeDetailModal() {
        document.getElementById('detail-modal').classList.add('hidden');
    }
    
    function editDetail(detail) {
        openDetailModal(detail);
    }
    
    document.getElementById('detail-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const detailId = document.getElementById('detail-id').value;
        const url = detailId 
            ? `<?= url('/admin/products/' . $product['id'] . '/details/') ?>${detailId}`
            : `<?= url('/admin/products/' . $product['id'] . '/details') ?>`;
        
        const data = {
            csrf_token: getCsrfToken(),
            title: document.getElementById('detail-title').value,
            content: document.getElementById('detail-content').value,
            sort_order: document.getElementById('detail-sort').value,
            status: document.getElementById('detail-status').value
        };
        
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const result = await response.json();
            
            if (result.success) {
                showToast(result.message, 'success');
                closeDetailModal();
                location.reload();
            } else {
                showToast(result.message, 'error');
            }
        } catch (error) {
            showToast('發生錯誤，請稍後再試', 'error');
        }
    });
    
    async function deleteDetail(detailId) {
        if (!confirm('確定要刪除此明細嗎？')) return;
        
        try {
            const response = await fetch(`<?= url('/admin/products/' . $product['id'] . '/details/') ?>${detailId}/delete`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ csrf_token: getCsrfToken() })
            });
            const result = await response.json();
            
            if (result.success) {
                showToast(result.message, 'success');
                document.querySelector(`[data-detail-id="${detailId}"]`).remove();
            } else {
                showToast(result.message, 'error');
            }
        } catch (error) {
            showToast('刪除失敗', 'error');
        }
    }
    
    // ==================== FAQ ====================
    function openFaqModal(faq = null) {
        document.getElementById('faq-modal-title').textContent = faq ? '編輯 Q&A' : '新增 Q&A';
        document.getElementById('faq-id').value = faq?.id || '';
        document.getElementById('faq-question').value = faq?.question || '';
        document.getElementById('faq-answer').value = faq?.answer || '';
        document.getElementById('faq-sort').value = faq?.sort_order || 0;
        document.getElementById('faq-status').value = faq?.status ?? 1;
        document.getElementById('faq-modal').classList.remove('hidden');
    }
    
    function closeFaqModal() {
        document.getElementById('faq-modal').classList.add('hidden');
    }
    
    function editFaq(faq) {
        openFaqModal(faq);
    }
    
    document.getElementById('faq-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const faqId = document.getElementById('faq-id').value;
        const url = faqId 
            ? `<?= url('/admin/products/' . $product['id'] . '/faqs/') ?>${faqId}`
            : `<?= url('/admin/products/' . $product['id'] . '/faqs') ?>`;
        
        const data = {
            csrf_token: getCsrfToken(),
            question: document.getElementById('faq-question').value,
            answer: document.getElementById('faq-answer').value,
            sort_order: document.getElementById('faq-sort').value,
            status: document.getElementById('faq-status').value
        };
        
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const result = await response.json();
            
            if (result.success) {
                showToast(result.message, 'success');
                closeFaqModal();
                location.reload();
            } else {
                showToast(result.message, 'error');
            }
        } catch (error) {
            showToast('發生錯誤，請稍後再試', 'error');
        }
    });
    
    async function deleteFaq(faqId) {
        if (!confirm('確定要刪除此 Q&A 嗎？')) return;
        
        try {
            const response = await fetch(`<?= url('/admin/products/' . $product['id'] . '/faqs/') ?>${faqId}/delete`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ csrf_token: getCsrfToken() })
            });
            const result = await response.json();
            
            if (result.success) {
                showToast(result.message, 'success');
                document.querySelector(`[data-faq-id="${faqId}"]`).remove();
            } else {
                showToast(result.message, 'error');
            }
        } catch (error) {
            showToast('刪除失敗', 'error');
        }
    }
    
    // ==================== 技術手冊 ====================
    let isEditingManual = false;
    
    function openManualModal(manual = null) {
        isEditingManual = !!manual;
        document.getElementById('manual-modal-title').textContent = manual ? '編輯手冊標題' : '上傳技術手冊';
        document.getElementById('manual-id').value = manual?.id || '';
        document.getElementById('manual-title').value = manual?.title || '';
        document.getElementById('manual-file-upload').style.display = manual ? 'none' : 'block';
        document.getElementById('manual-modal').classList.remove('hidden');
    }
    
    function closeManualModal() {
        document.getElementById('manual-modal').classList.add('hidden');
        document.getElementById('manual-file-name').textContent = '點擊選擇檔案';
    }
    
    function editManual(manual) {
        openManualModal(manual);
    }
    
    document.getElementById('manual-file').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            document.getElementById('manual-file-name').textContent = file.name;
        }
    });
    
    document.getElementById('manual-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const manualId = document.getElementById('manual-id').value;
        
        if (isEditingManual && manualId) {
            // 更新標題
            const data = {
                csrf_token: getCsrfToken(),
                title: document.getElementById('manual-title').value
            };
            
            try {
                const response = await fetch(`<?= url('/admin/products/' . $product['id'] . '/manuals/') ?>${manualId}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                
                if (result.success) {
                    showToast(result.message, 'success');
                    closeManualModal();
                    location.reload();
                } else {
                    showToast(result.message, 'error');
                }
            } catch (error) {
                showToast('發生錯誤，請稍後再試', 'error');
            }
        } else {
            // 上傳新檔案
            const fileInput = document.getElementById('manual-file');
            if (!fileInput.files.length) {
                showToast('請選擇檔案', 'error');
                return;
            }
            
            const formData = new FormData();
            formData.append('file', fileInput.files[0]);
            formData.append('title', document.getElementById('manual-title').value);
            formData.append('csrf_token', getCsrfToken());
            
            try {
                const response = await fetch(`<?= url('/admin/products/' . $product['id'] . '/manuals') ?>`, {
                    method: 'POST',
                    headers: { 'X-CSRF-Token': getCsrfToken() },
                    body: formData
                });
                const result = await response.json();
                
                if (result.success) {
                    showToast(result.message, 'success');
                    closeManualModal();
                    location.reload();
                } else {
                    showToast(result.message, 'error');
                }
            } catch (error) {
                showToast('上傳失敗', 'error');
            }
        }
    });
    
    async function deleteManual(manualId) {
        if (!confirm('確定要刪除此手冊嗎？')) return;
        
        try {
            const response = await fetch(`<?= url('/admin/products/' . $product['id'] . '/manuals/') ?>${manualId}/delete`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ csrf_token: getCsrfToken() })
            });
            const result = await response.json();
            
            if (result.success) {
                showToast(result.message, 'success');
                document.querySelector(`[data-manual-id="${manualId}"]`).remove();
            } else {
                showToast(result.message, 'error');
            }
        } catch (error) {
            showToast('刪除失敗', 'error');
        }
    }
</script>
