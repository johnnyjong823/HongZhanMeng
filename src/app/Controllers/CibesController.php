<?php
/**
 * Cibes Controller
 * Cibes 品牌維護控制器
 */

namespace App\Controllers;

use Core\Controller;
use App\Models\Cibes;
use App\Models\CodeDef;

class CibesController extends Controller
{
    private Cibes $cibesModel;
    private CodeDef $codeDefModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->cibesModel = new Cibes();
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
        
        $filters = [];
        if ($search) $filters['search'] = $search;
        if ($status !== '') $filters['status'] = $status;
        if ($category) $filters['category'] = $category;
        
        $result = $this->cibesModel->search($filters, $page, 15);
        $categories = $this->codeDefModel->getByType('cibes_category');
        
        $this->render('admin/cibes/index', [
            'title' => 'Cibes 品牌維護',
            'items' => $result['data'],
            'pagination' => $result['pagination'],
            'search' => $search,
            'status' => $status,
            'category' => $category,
            'categories' => $categories
        ], 'admin');
    }
    
    /**
     * 新增頁面
     */
    public function create(): void
    {
        $categories = $this->codeDefModel->getByType('cibes_category');
        
        $this->render('admin/cibes/create', [
            'title' => '新增 Cibes 品牌',
            'categories' => $categories,
            'nextSortOrder' => $this->cibesModel->getNextSortOrder()
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
        $name = trim($_POST['name'] ?? '');
        if (empty($name)) {
            $this->json(['success' => false, 'message' => '請填寫品牌名稱']);
            return;
        }
        
        // 準備資料
        $data = [
            'name' => $name,
            'category' => $_POST['category'] ?? null,
            'content' => trim($_POST['content'] ?? ''),
            'sort_order' => (int)($_POST['sort_order'] ?? $this->cibesModel->getNextSortOrder()),
            'status' => (int)($_POST['status'] ?? 1)
        ];
        
        // 處理圖片上傳（如果有）
        if (!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->cibesModel->uploadImage($_FILES['image']);
            if (!$imagePath) {
                $this->json(['success' => false, 'message' => '圖片上傳失敗，請確認檔案格式（JPG/PNG/GIF/WEBP）及大小（最大5MB）']);
                return;
            }
            $data['image_path'] = $imagePath;
        }
        
        $itemId = $this->cibesModel->create($data);
        
        if ($itemId) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Cibes 品牌已新增'];
            $this->json(['success' => true, 'message' => 'Cibes 品牌已新增', 'redirect' => url('/admin/cibes')]);
        } else {
            // 刪除已上傳的圖片
            if (!empty($data['image_path'])) {
                $this->cibesModel->deleteImageFile($data['image_path']);
            }
            $this->json(['success' => false, 'message' => '新增失敗，請稍後再試']);
        }
    }
    
    /**
     * 編輯頁面
     */
    public function edit(int $id): void
    {
        $item = $this->cibesModel->find($id);
        
        if (!$item) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => '找不到指定的 Cibes 品牌'];
            header('Location: ' . url('/admin/cibes'));
            exit;
        }
        
        $categories = $this->codeDefModel->getByType('cibes_category');
        
        $this->render('admin/cibes/edit', [
            'title' => '編輯 Cibes 品牌',
            'item' => $item,
            'categories' => $categories
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
        
        $item = $this->cibesModel->find($id);
        if (!$item) {
            $this->json(['success' => false, 'message' => '找不到指定的 Cibes 品牌']);
            return;
        }
        
        // 驗證必填欄位
        $name = trim($_POST['name'] ?? '');
        if (empty($name)) {
            $this->json(['success' => false, 'message' => '請填寫品牌名稱']);
            return;
        }
        
        // 準備資料
        $data = [
            'name' => $name,
            'category' => $_POST['category'] ?? null,
            'content' => trim($_POST['content'] ?? ''),
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
            'status' => (int)($_POST['status'] ?? 1)
        ];
        
        // 處理圖片上傳（如果有新圖片）
        if (!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->cibesModel->uploadImage($_FILES['image']);
            if (!$imagePath) {
                $this->json(['success' => false, 'message' => '圖片上傳失敗，請確認檔案格式（JPG/PNG/GIF/WEBP）及大小（最大5MB）']);
                return;
            }
            
            // 刪除舊圖片
            if (!empty($item['image_path'])) {
                $this->cibesModel->deleteImageFile($item['image_path']);
            }
            
            $data['image_path'] = $imagePath;
        }
        
        $result = $this->cibesModel->update($id, $data);
        
        if ($result) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Cibes 品牌已更新'];
            $this->json(['success' => true, 'message' => 'Cibes 品牌已更新', 'redirect' => url('/admin/cibes')]);
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
        
        $item = $this->cibesModel->find($id);
        if (!$item) {
            $this->json(['success' => false, 'message' => '找不到指定的 Cibes 品牌']);
            return;
        }
        
        $result = $this->cibesModel->delete($id);
        
        if ($result) {
            $this->json(['success' => true, 'message' => 'Cibes 品牌已刪除']);
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
        
        $result = $this->cibesModel->toggleStatus($id);
        
        if ($result) {
            $this->json(['success' => true, 'message' => '狀態已更新']);
        } else {
            $this->json(['success' => false, 'message' => '狀態更新失敗']);
        }
    }
}
