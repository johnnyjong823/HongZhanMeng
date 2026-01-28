<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\ProductFaq;
use App\Models\ProductManual;

class ProductController extends Controller
{
    protected $productModel;
    protected $detailModel;
    protected $faqModel;
    protected $manualModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->productModel = new Product();
        $this->detailModel = new ProductDetail();
        $this->faqModel = new ProductFaq();
        $this->manualModel = new ProductManual();
    }
    
    /**
     * 產品列表 (固定產品選擇頁)
     */
    public function index()
    {
        // 取得系統固定產品列表
        $products = $this->productModel->getSystemProducts();
        
        return $this->view('admin/products/index', [
            'title' => '產品維護',
            'products' => $products
        ], 'admin');
    }
    
    /**
     * 編輯產品頁面 (Tab 介面)
     */
    public function edit($id)
    {
        $product = $this->productModel->findWithAll($id);
        
        if (!$product) {
            return $this->redirect('/admin/products');
        }
        
        $tab = $_GET['tab'] ?? 'basic';
        
        return $this->view('admin/products/edit', [
            'title' => '編輯產品 - ' . $product['product_name'],
            'product' => $product,
            'activeTab' => $tab
        ], 'admin');
    }
    
    /**
     * 更新產品基本資訊
     */
    public function update($id)
    {
        if (!$this->verifyCsrf()) {
            return $this->json(['success' => false, 'message' => '安全驗證失敗']);
        }
        
        $product = $this->productModel->find($id);
        
        if (!$product) {
            return $this->json(['success' => false, 'message' => '產品不存在']);
        }
        
        $productName = trim($this->input('product_name', ''));
        $shortDescription = trim($this->input('short_description', ''));
        $description = trim($this->input('description', ''));
        $status = (int)$this->input('status', 1);
        
        // 驗證
        if (empty($productName)) {
            return $this->json(['success' => false, 'message' => '請輸入產品名稱']);
        }
        
        // 更新產品
        $this->productModel->update($id, [
            'product_name' => $productName,
            'short_description' => $shortDescription ?: null,
            'description' => $description ?: null,
            'status' => $status,
            'updated_by' => $_SESSION['user']['id']
        ]);
        
        return $this->json([
            'success' => true,
            'message' => '產品更新成功'
        ]);
    }
    
    // ==================== 產品明細 ====================
    
    /**
     * 新增產品明細
     */
    public function storeDetail($productId)
    {
        if (!$this->verifyCsrf()) {
            return $this->json(['success' => false, 'message' => '安全驗證失敗']);
        }
        
        $product = $this->productModel->find($productId);
        if (!$product) {
            return $this->json(['success' => false, 'message' => '產品不存在']);
        }
        
        $title = trim($this->input('title', ''));
        $content = trim($this->input('content', ''));
        $sortOrder = (int)$this->input('sort_order', 0);
        $status = (int)$this->input('status', 1);
        
        if (empty($title)) {
            return $this->json(['success' => false, 'message' => '請輸入標題']);
        }
        
        $detailId = $this->detailModel->create([
            'product_id' => $productId,
            'title' => $title,
            'content' => $content ?: null,
            'sort_order' => $sortOrder,
            'status' => $status,
            'created_by' => $_SESSION['user']['id']
        ]);
        
        return $this->json([
            'success' => true,
            'message' => '明細新增成功',
            'detail_id' => $detailId
        ]);
    }
    
    /**
     * 更新產品明細
     */
    public function updateDetail($productId, $detailId)
    {
        if (!$this->verifyCsrf()) {
            return $this->json(['success' => false, 'message' => '安全驗證失敗']);
        }
        
        $detail = $this->detailModel->find($detailId);
        if (!$detail || $detail['product_id'] != $productId) {
            return $this->json(['success' => false, 'message' => '明細不存在']);
        }
        
        $title = trim($this->input('title', ''));
        $content = trim($this->input('content', ''));
        $sortOrder = (int)$this->input('sort_order', 0);
        $status = (int)$this->input('status', 1);
        
        if (empty($title)) {
            return $this->json(['success' => false, 'message' => '請輸入標題']);
        }
        
        $this->detailModel->update($detailId, [
            'title' => $title,
            'content' => $content ?: null,
            'sort_order' => $sortOrder,
            'status' => $status,
            'updated_by' => $_SESSION['user']['id']
        ]);
        
        return $this->json([
            'success' => true,
            'message' => '明細更新成功'
        ]);
    }
    
    /**
     * 刪除產品明細
     */
    public function deleteDetail($productId, $detailId)
    {
        if (!$this->verifyCsrf()) {
            return $this->json(['success' => false, 'message' => '安全驗證失敗']);
        }
        
        $detail = $this->detailModel->find($detailId);
        if (!$detail || $detail['product_id'] != $productId) {
            return $this->json(['success' => false, 'message' => '明細不存在']);
        }
        
        $this->detailModel->deleteWithFile($detailId);
        
        return $this->json([
            'success' => true,
            'message' => '明細已刪除'
        ]);
    }
    
    /**
     * 上傳明細圖片
     */
    public function uploadDetailImage($productId, $detailId)
    {
        if (!$this->verifyCsrf()) {
            return $this->json(['success' => false, 'message' => '安全驗證失敗']);
        }
        
        $detail = $this->detailModel->find($detailId);
        if (!$detail || $detail['product_id'] != $productId) {
            return $this->json(['success' => false, 'message' => '明細不存在']);
        }
        
        if (empty($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            return $this->json(['success' => false, 'message' => '請選擇圖片檔案']);
        }
        
        $file = $_FILES['image'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        
        if (!in_array($file['type'], $allowedTypes)) {
            return $this->json(['success' => false, 'message' => '只支援 JPG、PNG、GIF、WebP 圖片格式']);
        }
        
        if ($file['size'] > 5 * 1024 * 1024) {
            return $this->json(['success' => false, 'message' => '圖片大小不能超過 5MB']);
        }
        
        // 刪除舊圖片
        if (!empty($detail['image_path'])) {
            $oldPath = ROOT_PATH . '/public' . $detail['image_path'];
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }
        
        // 建立上傳目錄
        $uploadDir = '/uploads/products/' . $productId . '/details';
        $uploadPath = ROOT_PATH . '/public' . $uploadDir;
        
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFilename = 'detail_' . $detailId . '_' . time() . '.' . $ext;
        $filePath = $uploadPath . '/' . $newFilename;
        $dbPath = $uploadDir . '/' . $newFilename;
        
        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            return $this->json(['success' => false, 'message' => '圖片上傳失敗']);
        }
        
        $this->detailModel->update($detailId, [
            'image_path' => $dbPath,
            'image_filename' => $file['name']
        ]);
        
        return $this->json([
            'success' => true,
            'message' => '圖片上傳成功',
            'image_path' => $dbPath
        ]);
    }
    
    // ==================== 產品 FAQ ====================
    
    /**
     * 新增 FAQ
     */
    public function storeFaq($productId)
    {
        if (!$this->verifyCsrf()) {
            return $this->json(['success' => false, 'message' => '安全驗證失敗']);
        }
        
        $product = $this->productModel->find($productId);
        if (!$product) {
            return $this->json(['success' => false, 'message' => '產品不存在']);
        }
        
        $question = trim($this->input('question', ''));
        $answer = trim($this->input('answer', ''));
        $sortOrder = (int)$this->input('sort_order', 0);
        $status = (int)$this->input('status', 1);
        
        if (empty($question)) {
            return $this->json(['success' => false, 'message' => '請輸入問題']);
        }
        
        if (empty($answer)) {
            return $this->json(['success' => false, 'message' => '請輸入回答']);
        }
        
        $faqId = $this->faqModel->create([
            'product_id' => $productId,
            'question' => $question,
            'answer' => $answer,
            'sort_order' => $sortOrder,
            'status' => $status,
            'created_by' => $_SESSION['user']['id']
        ]);
        
        return $this->json([
            'success' => true,
            'message' => 'Q&A 新增成功',
            'faq_id' => $faqId
        ]);
    }
    
    /**
     * 更新 FAQ
     */
    public function updateFaq($productId, $faqId)
    {
        if (!$this->verifyCsrf()) {
            return $this->json(['success' => false, 'message' => '安全驗證失敗']);
        }
        
        $faq = $this->faqModel->find($faqId);
        if (!$faq || $faq['product_id'] != $productId) {
            return $this->json(['success' => false, 'message' => 'Q&A 不存在']);
        }
        
        $question = trim($this->input('question', ''));
        $answer = trim($this->input('answer', ''));
        $sortOrder = (int)$this->input('sort_order', 0);
        $status = (int)$this->input('status', 1);
        
        if (empty($question)) {
            return $this->json(['success' => false, 'message' => '請輸入問題']);
        }
        
        if (empty($answer)) {
            return $this->json(['success' => false, 'message' => '請輸入回答']);
        }
        
        $this->faqModel->update($faqId, [
            'question' => $question,
            'answer' => $answer,
            'sort_order' => $sortOrder,
            'status' => $status,
            'updated_by' => $_SESSION['user']['id']
        ]);
        
        return $this->json([
            'success' => true,
            'message' => 'Q&A 更新成功'
        ]);
    }
    
    /**
     * 刪除 FAQ
     */
    public function deleteFaq($productId, $faqId)
    {
        if (!$this->verifyCsrf()) {
            return $this->json(['success' => false, 'message' => '安全驗證失敗']);
        }
        
        $faq = $this->faqModel->find($faqId);
        if (!$faq || $faq['product_id'] != $productId) {
            return $this->json(['success' => false, 'message' => 'Q&A 不存在']);
        }
        
        $this->faqModel->delete($faqId);
        
        return $this->json([
            'success' => true,
            'message' => 'Q&A 已刪除'
        ]);
    }
    
    // ==================== 技術手冊 ====================
    
    /**
     * 上傳技術手冊
     */
    public function uploadManualFile($productId)
    {
        if (!$this->verifyCsrf()) {
            return $this->json(['success' => false, 'message' => '安全驗證失敗']);
        }
        
        $product = $this->productModel->find($productId);
        if (!$product) {
            return $this->json(['success' => false, 'message' => '產品不存在']);
        }
        
        if (empty($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            return $this->json(['success' => false, 'message' => '請選擇檔案']);
        }
        
        $file = $_FILES['file'];
        $title = trim($this->input('title', ''));
        
        if (empty($title)) {
            $title = pathinfo($file['name'], PATHINFO_FILENAME);
        }
        
        $allowedTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation'
        ];
        
        if (!in_array($file['type'], $allowedTypes)) {
            return $this->json(['success' => false, 'message' => '只支援 PDF、Word、Excel、PowerPoint 檔案格式']);
        }
        
        if ($file['size'] > 50 * 1024 * 1024) {
            return $this->json(['success' => false, 'message' => '檔案大小不能超過 50MB']);
        }
        
        // 建立上傳目錄
        $uploadDir = '/uploads/products/' . $productId . '/manuals';
        $uploadPath = ROOT_PATH . '/public' . $uploadDir;
        
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $newFilename = 'manual_' . time() . '_' . uniqid() . '.' . $ext;
        $filePath = $uploadPath . '/' . $newFilename;
        $dbPath = $uploadDir . '/' . $newFilename;
        
        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            return $this->json(['success' => false, 'message' => '檔案上傳失敗']);
        }
        
        $manualId = $this->manualModel->create([
            'product_id' => $productId,
            'title' => $title,
            'file_path' => $dbPath,
            'filename' => $file['name'],
            'file_type' => $ext,
            'file_size' => $file['size'],
            'created_by' => $_SESSION['user']['id']
        ]);
        
        return $this->json([
            'success' => true,
            'message' => '手冊上傳成功',
            'manual' => [
                'id' => $manualId,
                'title' => $title,
                'file_path' => $dbPath,
                'filename' => $file['name'],
                'file_type' => $ext,
                'file_size' => $file['size'],
                'file_size_formatted' => ProductManual::formatFileSize($file['size']),
                'file_icon' => ProductManual::getFileIcon($ext)
            ]
        ]);
    }
    
    /**
     * 更新手冊標題
     */
    public function updateManual($productId, $manualId)
    {
        if (!$this->verifyCsrf()) {
            return $this->json(['success' => false, 'message' => '安全驗證失敗']);
        }
        
        $manual = $this->manualModel->find($manualId);
        if (!$manual || $manual['product_id'] != $productId) {
            return $this->json(['success' => false, 'message' => '手冊不存在']);
        }
        
        $title = trim($this->input('title', ''));
        $sortOrder = (int)$this->input('sort_order', 0);
        
        if (empty($title)) {
            return $this->json(['success' => false, 'message' => '請輸入手冊名稱']);
        }
        
        $this->manualModel->update($manualId, [
            'title' => $title,
            'sort_order' => $sortOrder
        ]);
        
        return $this->json([
            'success' => true,
            'message' => '手冊更新成功'
        ]);
    }
    
    /**
     * 刪除技術手冊
     */
    public function deleteManualFile($productId, $manualId)
    {
        if (!$this->verifyCsrf()) {
            return $this->json(['success' => false, 'message' => '安全驗證失敗']);
        }
        
        $manual = $this->manualModel->find($manualId);
        if (!$manual || $manual['product_id'] != $productId) {
            return $this->json(['success' => false, 'message' => '手冊不存在']);
        }
        
        $this->manualModel->deleteWithFile($manualId);
        
        return $this->json([
            'success' => true,
            'message' => '手冊已刪除'
        ]);
    }
    
    // ==================== 產品圖片 ====================
    
    /**
     * 上傳圖片
     */
    public function uploadImage($id)
    {
        if (!$this->verifyCsrf()) {
            return $this->json(['success' => false, 'message' => '安全驗證失敗']);
        }
        
        $product = $this->productModel->find($id);
        
        if (!$product) {
            return $this->json(['success' => false, 'message' => '產品不存在']);
        }
        
        if (empty($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            return $this->json(['success' => false, 'message' => '請選擇圖片檔案']);
        }
        
        $file = $_FILES['image'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        
        if (!in_array($file['type'], $allowedTypes)) {
            return $this->json(['success' => false, 'message' => '只支援 JPG、PNG、GIF、WebP 圖片格式']);
        }
        
        // 檔案大小限制 5MB
        if ($file['size'] > 5 * 1024 * 1024) {
            return $this->json(['success' => false, 'message' => '圖片大小不能超過 5MB']);
        }
        
        // 建立上傳目錄
        $uploadDir = '/uploads/products/' . $id;
        $uploadPath = ROOT_PATH . '/public' . $uploadDir;
        
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        
        // 產生唯一檔名
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFilename = uniqid() . '_' . time() . '.' . $ext;
        $filePath = $uploadPath . '/' . $newFilename;
        $dbPath = $uploadDir . '/' . $newFilename;
        
        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            return $this->json(['success' => false, 'message' => '圖片上傳失敗']);
        }
        
        // 判斷是否為第一張圖片 (設為主圖)
        $existingImages = $this->productModel->getImages($id);
        $imageType = empty($existingImages) ? 'main' : 'gallery';
        
        // 儲存到資料庫
        $imageId = $this->productModel->addImage($id, $dbPath, $file['name'], $imageType);
        
        return $this->json([
            'success' => true,
            'message' => '圖片上傳成功',
            'image' => [
                'id' => $imageId,
                'path' => $dbPath,
                'filename' => $file['name'],
                'type' => $imageType
            ]
        ]);
    }
    
    /**
     * 刪除圖片
     */
    public function deleteImage($id, $imageId)
    {
        if (!$this->verifyCsrf()) {
            return $this->json(['success' => false, 'message' => '安全驗證失敗']);
        }
        
        $this->productModel->deleteImage($imageId);
        
        return $this->json([
            'success' => true,
            'message' => '圖片已刪除'
        ]);
    }
    
    /**
     * 設定主圖
     */
    public function setMainImage($id, $imageId)
    {
        if (!$this->verifyCsrf()) {
            return $this->json(['success' => false, 'message' => '安全驗證失敗']);
        }
        
        $this->productModel->setMainImage($id, $imageId);
        
        return $this->json([
            'success' => true,
            'message' => '已設定為主圖'
        ]);
    }
}
