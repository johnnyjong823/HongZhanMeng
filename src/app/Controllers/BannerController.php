<?php
/**
 * Banner Controller
 * 圖片輪播維護控制器
 */

namespace App\Controllers;

use Core\Controller;
use App\Models\Banner;

class BannerController extends Controller
{
    private Banner $bannerModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->bannerModel = new Banner();
    }
    
    /**
     * 列表頁
     */
    public function index(): void
    {
        $page = (int)($_GET['page'] ?? 1);
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';
        $position = $_GET['position'] ?? '';
        
        $filters = [];
        if ($search) $filters['search'] = $search;
        if ($status !== '') $filters['status'] = $status;
        if ($position) $filters['position'] = $position;
        
        $result = $this->bannerModel->search($filters, $page, 15);
        
        $this->render('admin/banners/index', [
            'title' => '圖片輪播維護',
            'banners' => $result['data'],
            'pagination' => $result['pagination'],
            'search' => $search,
            'status' => $status,
            'position' => $position
        ], 'admin');
    }
    
    /**
     * 新增頁面
     */
    public function create(): void
    {
        $position = $_GET['position'] ?? Banner::POSITION_HERO;
        
        $this->render('admin/banners/create', [
            'title' => '新增輪播',
            'nextSortOrder' => $this->bannerModel->getNextSortOrder($position),
            'nextSortOrderHero' => $this->bannerModel->getNextSortOrder(Banner::POSITION_HERO),
            'nextSortOrderFeatures' => $this->bannerModel->getNextSortOrder(Banner::POSITION_FEATURES),
            'position' => $position
        ], 'admin');
    }
    
    /**
     * 儲存新增
     */
    public function store(): void
    {
        // 檢查是否 POST 資料因超過限制而被清空
        if (empty($_POST) && empty($_FILES) && isset($_SERVER['CONTENT_LENGTH']) && $_SERVER['CONTENT_LENGTH'] > 0) {
            $maxSize = $this->getMaxUploadSize();
            $this->json(['success' => false, 'message' => "上傳檔案過大，伺服器限制為 {$maxSize}。請先壓縮檔案後再上傳。"]);
            return;
        }
        
        if (!$this->verifyCsrf()) {
            $this->json(['success' => false, 'message' => 'CSRF token 驗證失敗']);
            return;
        }
        
        // 驗證必填欄位
        $title = trim($_POST['title'] ?? '');
        if (empty($title)) {
            $this->json(['success' => false, 'message' => '請填寫標題']);
            return;
        }
        
        $position = $_POST['position'] ?? Banner::POSITION_HERO;
        $mediaType = $_POST['media_type'] ?? Banner::MEDIA_IMAGE;
        
        // 下方三圖輪播只能是圖片
        if ($position === Banner::POSITION_FEATURES) {
            $mediaType = Banner::MEDIA_IMAGE;
        }
        
        $imagePath = null;
        $videoPath = null;
        
        // 處理媒體上傳
        if ($mediaType === Banner::MEDIA_VIDEO) {
            // 影片上傳
            if (empty($_FILES['video']) || $_FILES['video']['error'] !== UPLOAD_ERR_OK) {
                $errorMsg = $this->getUploadErrorMessage($_FILES['video']['error'] ?? UPLOAD_ERR_NO_FILE, 'video');
                $this->json(['success' => false, 'message' => $errorMsg]);
                return;
            }
            
            $videoPath = $this->bannerModel->uploadVideo($_FILES['video']);
            if (!$videoPath) {
                $this->json(['success' => false, 'message' => '影片上傳失敗，請確認檔案格式（MP4/WEBM/OGG）及大小（最大50MB）']);
                return;
            }
        } else {
            // 圖片上傳
            if (empty($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                $errorMsg = $this->getUploadErrorMessage($_FILES['image']['error'] ?? UPLOAD_ERR_NO_FILE, 'image');
                $this->json(['success' => false, 'message' => $errorMsg]);
                return;
            }
            
            $imagePath = $this->bannerModel->uploadImage($_FILES['image']);
            if (!$imagePath) {
                $this->json(['success' => false, 'message' => '圖片上傳失敗，請確認檔案格式（JPG/PNG/GIF/WEBP）及大小（最大10MB）']);
                return;
            }
        }
        
        // 準備資料
        $data = [
            'position' => $position,
            'media_type' => $mediaType,
            'title' => $title,
            'description' => trim($_POST['description'] ?? ''),
            'image_path' => $imagePath,
            'video_path' => $videoPath,
            'link_url' => trim($_POST['link_url'] ?? ''),
            'link_target' => $_POST['link_target'] ?? '_self',
            'sort_order' => (int)($_POST['sort_order'] ?? $this->bannerModel->getNextSortOrder($position)),
            'status' => (int)($_POST['status'] ?? 1),
            'start_date' => $_POST['start_date'] ?? null,
            'end_date' => $_POST['end_date'] ?? null
        ];
        
        $bannerId = $this->bannerModel->create($data);
        
        if ($bannerId) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => '輪播已新增'];
            $this->json(['success' => true, 'message' => '輪播已新增', 'redirect' => url('/admin/banners')]);
        } else {
            // 刪除已上傳的檔案
            if ($imagePath) $this->bannerModel->deleteImageFile($imagePath);
            if ($videoPath) $this->bannerModel->deleteVideoFile($videoPath);
            $this->json(['success' => false, 'message' => '新增失敗，請稍後再試']);
        }
    }
    
    /**
     * 編輯頁面
     */
    public function edit(int $id): void
    {
        $banner = $this->bannerModel->find($id);
        
        if (!$banner) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => '找不到指定的輪播圖片'];
            header('Location: ' . url('/admin/banners'));
            exit;
        }
        
        $this->render('admin/banners/edit', [
            'title' => '編輯輪播圖片',
            'banner' => $banner
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
        
        $banner = $this->bannerModel->find($id);
        if (!$banner) {
            $this->json(['success' => false, 'message' => '找不到指定的輪播']);
            return;
        }
        
        // 驗證必填欄位
        $title = trim($_POST['title'] ?? '');
        if (empty($title)) {
            $this->json(['success' => false, 'message' => '請填寫標題']);
            return;
        }
        
        $position = $_POST['position'] ?? $banner['position'] ?? Banner::POSITION_HERO;
        $mediaType = $_POST['media_type'] ?? $banner['media_type'] ?? Banner::MEDIA_IMAGE;
        
        // 下方三圖輪播只能是圖片
        if ($position === Banner::POSITION_FEATURES) {
            $mediaType = Banner::MEDIA_IMAGE;
        }
        
        // 準備資料
        $data = [
            'position' => $position,
            'media_type' => $mediaType,
            'title' => $title,
            'description' => trim($_POST['description'] ?? ''),
            'link_url' => trim($_POST['link_url'] ?? ''),
            'link_target' => $_POST['link_target'] ?? '_self',
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
            'status' => (int)($_POST['status'] ?? 1),
            'start_date' => $_POST['start_date'] ?? null,
            'end_date' => $_POST['end_date'] ?? null
        ];
        
        // 處理圖片上傳（如果有新圖片）
        if (!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->bannerModel->uploadImage($_FILES['image']);
            if (!$imagePath) {
                $this->json(['success' => false, 'message' => '圖片上傳失敗，請確認檔案格式（JPG/PNG/GIF/WEBP）及大小（最大10MB）']);
                return;
            }
            
            // 刪除舊圖片
            if (!empty($banner['image_path'])) {
                $this->bannerModel->deleteImageFile($banner['image_path']);
            }
            
            $data['image_path'] = $imagePath;
        }
        
        // 處理影片上傳（如果有新影片）
        if (!empty($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
            $videoPath = $this->bannerModel->uploadVideo($_FILES['video']);
            if (!$videoPath) {
                $this->json(['success' => false, 'message' => '影片上傳失敗，請確認檔案格式（MP4/WEBM/OGG）及大小（最大50MB）']);
                return;
            }
            
            // 刪除舊影片
            if (!empty($banner['video_path'])) {
                $this->bannerModel->deleteVideoFile($banner['video_path']);
            }
            
            $data['video_path'] = $videoPath;
        }
        
        $result = $this->bannerModel->update($id, $data);
        
        if ($result) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => '輪播已更新'];
            $this->json(['success' => true, 'message' => '輪播已更新', 'redirect' => url('/admin/banners')]);
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
        
        $banner = $this->bannerModel->find($id);
        if (!$banner) {
            $this->json(['success' => false, 'message' => '找不到指定的輪播圖片']);
            return;
        }
        
        $result = $this->bannerModel->delete($id);
        
        if ($result) {
            $this->json(['success' => true, 'message' => '輪播圖片已刪除']);
        } else {
            $this->json(['success' => false, 'message' => '刪除失敗，請稍後再試']);
        }
    }
    
    /**
     * 更新排序
     */
    public function updateSort(): void
    {
        if (!$this->verifyCsrf()) {
            $this->json(['success' => false, 'message' => 'CSRF token 驗證失敗']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $items = $input['items'] ?? [];
        
        if (empty($items)) {
            $this->json(['success' => false, 'message' => '沒有排序資料']);
            return;
        }
        
        $result = $this->bannerModel->updateSortOrders($items);
        
        if ($result) {
            $this->json(['success' => true, 'message' => '排序已更新']);
        } else {
            $this->json(['success' => false, 'message' => '排序更新失敗']);
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
        
        $result = $this->bannerModel->toggleStatus($id);
        
        if ($result) {
            $this->json(['success' => true, 'message' => '狀態已更新']);
        } else {
            $this->json(['success' => false, 'message' => '狀態更新失敗']);
        }
    }
    
    /**
     * 排序頁面
     */
    public function sort(): void
    {
        $heroBanners = $this->bannerModel->getByPosition(Banner::POSITION_HERO, false);
        $featuresBanners = $this->bannerModel->getByPosition(Banner::POSITION_FEATURES, false);
        
        $this->render('admin/banners/sort', [
            'title' => '輪播圖片排序',
            'heroBanners' => $heroBanners,
            'featuresBanners' => $featuresBanners
        ], 'admin');
    }
    
    /**
     * 取得最大上傳大小（格式化顯示）
     */
    private function getMaxUploadSize(): string
    {
        $uploadMax = $this->convertToBytes(ini_get('upload_max_filesize'));
        $postMax = $this->convertToBytes(ini_get('post_max_size'));
        $maxBytes = min($uploadMax, $postMax);
        
        if ($maxBytes >= 1073741824) {
            return round($maxBytes / 1073741824, 1) . 'GB';
        } elseif ($maxBytes >= 1048576) {
            return round($maxBytes / 1048576, 1) . 'MB';
        } else {
            return round($maxBytes / 1024, 1) . 'KB';
        }
    }
    
    /**
     * 轉換 PHP ini 大小設定為位元組
     */
    private function convertToBytes(string $value): int
    {
        $value = trim($value);
        $last = strtolower($value[strlen($value) - 1]);
        $value = (int)$value;
        
        switch ($last) {
            case 'g': $value *= 1024;
            case 'm': $value *= 1024;
            case 'k': $value *= 1024;
        }
        
        return $value;
    }
    
    /**
     * 取得上傳錯誤訊息
     */
    private function getUploadErrorMessage(int $errorCode, string $type = 'image'): string
    {
        $typeName = $type === 'video' ? '影片' : '圖片';
        $maxSize = $this->getMaxUploadSize();
        
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return "上傳的{$typeName}檔案過大，伺服器限制為 {$maxSize}。請先壓縮檔案後再上傳。";
            case UPLOAD_ERR_PARTIAL:
                return "{$typeName}上傳不完整，請重新上傳";
            case UPLOAD_ERR_NO_FILE:
                return "請上傳{$typeName}";
            case UPLOAD_ERR_NO_TMP_DIR:
                return "伺服器暫存目錄不存在，請聯繫管理員";
            case UPLOAD_ERR_CANT_WRITE:
                return "無法寫入檔案，請聯繫管理員";
            case UPLOAD_ERR_EXTENSION:
                return "上傳被伺服器擴展阻止，請聯繫管理員";
            default:
                return "請上傳{$typeName}";
        }
    }
}
