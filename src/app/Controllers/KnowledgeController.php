<?php
/**
 * Knowledge Controller
 * 知識分享維護控制器
 */

namespace App\Controllers;

use Core\Controller;
use App\Models\Knowledge;
use App\Models\CodeDef;

class KnowledgeController extends Controller
{
    private Knowledge $knowledgeModel;
    private CodeDef $codeDefModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->knowledgeModel = new Knowledge();
        $this->codeDefModel = new CodeDef();
    }
    
    /**
     * 列表頁
     */
    public function index(): void
    {
        $page = (int)($_GET['page'] ?? 1);
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';
        $category = $_GET['category'] ?? '';
        $isPinned = $_GET['is_pinned'] ?? '';
        
        $filters = [];
        if ($search) $filters['search'] = $search;
        if ($status !== '') $filters['status'] = $status;
        if ($category) $filters['category'] = $category;
        if ($isPinned !== '') $filters['is_pinned'] = $isPinned;
        
        $result = $this->knowledgeModel->search($filters, $page, 15);
        $categories = $this->codeDefModel->getByType('knowledge_category');
        $pinnedCount = $this->knowledgeModel->countPinned();
        
        $this->render('admin/knowledge/index', [
            'title' => '知識分享維護',
            'items' => $result['data'],
            'pagination' => $result['pagination'],
            'search' => $search,
            'status' => $status,
            'category' => $category,
            'isPinned' => $isPinned,
            'categories' => $categories,
            'pinnedCount' => $pinnedCount,
            'maxPinned' => Knowledge::MAX_PINNED
        ], 'admin');
    }
    
    /**
     * 新增頁面
     */
    public function create(): void
    {
        $categories = $this->codeDefModel->getByType('knowledge_category');
        $canAddPinned = $this->knowledgeModel->canAddPinned();
        $pinnedCount = $this->knowledgeModel->countPinned();
        
        $this->render('admin/knowledge/create', [
            'title' => '新增知識分享',
            'categories' => $categories,
            'nextSortOrder' => $this->knowledgeModel->getNextSortOrder(),
            'canAddPinned' => $canAddPinned,
            'pinnedCount' => $pinnedCount,
            'maxPinned' => Knowledge::MAX_PINNED
        ], 'admin');
    }
    
    /**
     * 儲存新增
     */
    public function store(): void
    {
        if (!$this->verifyCsrf()) {
            $this->json(['success' => false, 'message' => 'CSRF token 驗證失敗']);
            return;
        }
        
        // 驗證必填欄位
        $title = trim($_POST['title'] ?? '');
        $category = trim($_POST['category'] ?? '');
        
        if (empty($title)) {
            $this->json(['success' => false, 'message' => '請填寫知識標題']);
            return;
        }
        
        if (empty($category)) {
            $this->json(['success' => false, 'message' => '請選擇知識類別']);
            return;
        }
        
        // 檢查置頂數量
        $isPinned = (int)($_POST['is_pinned'] ?? 0);
        if ($isPinned && !$this->knowledgeModel->canAddPinned()) {
            $this->json(['success' => false, 'message' => '置頂數量已達上限（最多 ' . Knowledge::MAX_PINNED . ' 個）']);
            return;
        }
        
        // 準備資料
        $data = [
            'title' => $title,
            'category' => $category,
            'content' => trim($_POST['content'] ?? ''),
            'is_pinned' => $isPinned,
            'sort_order' => (int)($_POST['sort_order'] ?? $this->knowledgeModel->getNextSortOrder()),
            'status' => (int)($_POST['status'] ?? 1)
        ];
        
        // 處理圖片上傳（如果有）
        if (!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->knowledgeModel->uploadImage($_FILES['image']);
            if (!$imagePath) {
                $this->json(['success' => false, 'message' => '圖片上傳失敗，請確認檔案格式（JPG/PNG/GIF/WEBP）及大小（最大5MB）']);
                return;
            }
            $data['image_path'] = $imagePath;
        }
        
        $itemId = $this->knowledgeModel->create($data);
        
        if ($itemId) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => '知識分享已新增'];
            $this->json(['success' => true, 'message' => '知識分享已新增', 'redirect' => '/admin/knowledge']);
        } else {
            // 刪除已上傳的圖片
            if (!empty($data['image_path'])) {
                $this->knowledgeModel->deleteImageFile($data['image_path']);
            }
            $this->json(['success' => false, 'message' => '新增失敗，請稍後再試']);
        }
    }
    
    /**
     * 編輯頁面
     */
    public function edit(int $id): void
    {
        $item = $this->knowledgeModel->find($id);
        
        if (!$item) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => '找不到指定的知識分享'];
            header('Location: /admin/knowledge');
            exit;
        }
        
        $categories = $this->codeDefModel->getByType('knowledge_category');
        $canAddPinned = $this->knowledgeModel->canAddPinned($id);
        $pinnedCount = $this->knowledgeModel->countPinned();
        
        $this->render('admin/knowledge/edit', [
            'title' => '編輯知識分享',
            'item' => $item,
            'categories' => $categories,
            'canAddPinned' => $canAddPinned,
            'pinnedCount' => $pinnedCount,
            'maxPinned' => Knowledge::MAX_PINNED
        ], 'admin');
    }
    
    /**
     * 更新
     */
    public function update(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->json(['success' => false, 'message' => 'CSRF token 驗證失敗']);
            return;
        }
        
        $item = $this->knowledgeModel->find($id);
        if (!$item) {
            $this->json(['success' => false, 'message' => '找不到指定的知識分享']);
            return;
        }
        
        // 驗證必填欄位
        $title = trim($_POST['title'] ?? '');
        $category = trim($_POST['category'] ?? '');
        
        if (empty($title)) {
            $this->json(['success' => false, 'message' => '請填寫知識標題']);
            return;
        }
        
        if (empty($category)) {
            $this->json(['success' => false, 'message' => '請選擇知識類別']);
            return;
        }
        
        // 檢查置頂數量（如果從非置頂改為置頂）
        $isPinned = (int)($_POST['is_pinned'] ?? 0);
        if ($isPinned && $item['is_pinned'] == 0 && !$this->knowledgeModel->canAddPinned($id)) {
            $this->json(['success' => false, 'message' => '置頂數量已達上限（最多 ' . Knowledge::MAX_PINNED . ' 個）']);
            return;
        }
        
        // 準備資料
        $data = [
            'title' => $title,
            'category' => $category,
            'content' => trim($_POST['content'] ?? ''),
            'is_pinned' => $isPinned,
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
            'status' => (int)($_POST['status'] ?? 1)
        ];
        
        // 處理圖片上傳（如果有新圖片）
        if (!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->knowledgeModel->uploadImage($_FILES['image']);
            if (!$imagePath) {
                $this->json(['success' => false, 'message' => '圖片上傳失敗，請確認檔案格式（JPG/PNG/GIF/WEBP）及大小（最大5MB）']);
                return;
            }
            
            // 刪除舊圖片
            if (!empty($item['image_path'])) {
                $this->knowledgeModel->deleteImageFile($item['image_path']);
            }
            
            $data['image_path'] = $imagePath;
        }
        
        $result = $this->knowledgeModel->update($id, $data);
        
        if ($result) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => '知識分享已更新'];
            $this->json(['success' => true, 'message' => '知識分享已更新', 'redirect' => '/admin/knowledge']);
        } else {
            $this->json(['success' => false, 'message' => '更新失敗，請稍後再試']);
        }
    }
    
    /**
     * 刪除
     */
    public function delete(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->json(['success' => false, 'message' => 'CSRF token 驗證失敗']);
            return;
        }
        
        $item = $this->knowledgeModel->find($id);
        if (!$item) {
            $this->json(['success' => false, 'message' => '找不到指定的知識分享']);
            return;
        }
        
        $result = $this->knowledgeModel->delete($id);
        
        if ($result) {
            $this->json(['success' => true, 'message' => '知識分享已刪除']);
        } else {
            $this->json(['success' => false, 'message' => '刪除失敗，請稍後再試']);
        }
    }
    
    /**
     * 切換狀態
     */
    public function toggleStatus(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->json(['success' => false, 'message' => 'CSRF token 驗證失敗']);
            return;
        }
        
        $result = $this->knowledgeModel->toggleStatus($id);
        
        if ($result) {
            $this->json(['success' => true, 'message' => '狀態已更新']);
        } else {
            $this->json(['success' => false, 'message' => '狀態更新失敗']);
        }
    }
    
    /**
     * 切換置頂狀態
     */
    public function togglePinned(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->json(['success' => false, 'message' => 'CSRF token 驗證失敗']);
            return;
        }
        
        $result = $this->knowledgeModel->togglePinned($id);
        $this->json($result);
    }
}
