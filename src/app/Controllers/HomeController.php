<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\Knowledge;
use App\Models\CodeDef;
use App\Models\Banner;
use App\Models\Product;
use App\Models\ProductFaq;

class HomeController extends Controller
{
    protected $skipActionFilter = ['index', 'about', 'contact', 'productsAscenda', 'productsDimensions', 'productsSpecs', 'productsFaq', 'aboutHongZhanMeng', 'aboutCibes', 'knowledge', 'knowledgeDetail', 'contactPage'];
    
    private Knowledge $knowledgeModel;
    private Banner $bannerModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->knowledgeModel = new Knowledge();
        $this->bannerModel = new Banner();
    }
    
    /**
     * 前台首頁
     */
    public function index()
    {
        // 取得置頂的知識分享（最多3筆）
        $pinnedKnowledge = $this->knowledgeModel->getPinned(3);
        
        // 取得首頁主輪播
        $heroBanners = $this->bannerModel->getHeroBanners();
        
        // 取得下方三圖輪播
        $featuresBanners = $this->bannerModel->getFeaturesBanners();
        
        return $this->view('frontend/home/index', [
            'title' => 'Ascenda 愛升達家用電梯｜瑞典 Cibes 台灣總代理',
            'description' => '源自瑞典的高效能質感電梯，無底坑、無機房設計，安裝只需 5-7 天。靜音螺桿驅動技術，節省約 45% 用電量。',
            'pinnedKnowledge' => $pinnedKnowledge,
            'heroBanners' => $heroBanners,
            'featuresBanners' => $featuresBanners
        ], 'frontend');
    }
    
    /**
     * 關於我們 (舊版，保留相容性)
     */
    public function about()
    {
        return $this->view('frontend/home/about', [
            'title' => '關於我們 - 鴻展盟'
        ], 'frontend');
    }
    
    /**
     * 關於鴻展盟
     */
    public function aboutHongZhanMeng()
    {
        return $this->view('frontend/about/about', [
            'title' => '關於鴻展盟｜Cibes 台灣總代理',
            'description' => '鴻展盟科技有限公司為瑞典知名電梯品牌 Cibes Lift Group 在台灣的總代理，專注於引進高品質、節省空間的家用電梯解決方案。'
        ], 'frontend');
    }
    
    /**
     * 關於 Cibes
     */
    public function aboutCibes()
    {
        return $this->view('frontend/cibes/cibes', [
            'title' => '關於 Cibes｜瑞典頂級電梯品牌',
            'description' => 'Cibes Lift Group 是全球最大的預製式、節省空間型電梯製造商之一，源自 1947 年瑞典，業務遍及 70 多個國家。'
        ], 'frontend');
    }
    
    /**
     * 聯絡我們
     */
    public function contact()
    {
        return $this->view('frontend/home/contact', [
            'title' => '聯絡我們 - 鴻展盟'
        ], 'frontend');
    }
    
    /**
     * Ascenda 愛升達產品頁
     */
    public function productsAscenda()
    {
        return $this->view('frontend/products/ascenda', [
            'title' => 'Ascenda 愛升達｜瑞典頂尖技術家用電梯',
            'description' => 'Ascenda Minivator 是瑞典大廠 Cibes Lift Group 專為兩層樓住宅設計的家用電梯解答，採用獨特的螺桿驅動系統。'
        ], 'frontend');
    }
    
    /**
     * 尺寸與安裝
     */
    public function productsDimensions()
    {
        // 取得愛生達產品的 FAQ
        $productModel = new Product();
        $productFaqModel = new ProductFaq();
        
        $faqs = [];
        $product = $productModel->findBySlug('ascenda');
        if ($product) {
            $faqs = $productFaqModel->getByProductId($product['id'], true);
        }
        
        return $this->view('frontend/products/dimensions', [
            'title' => '尺寸與安裝｜Ascenda 愛升達家用電梯',
            'description' => '挑選適合您的尺寸與規格，每種尺寸均有黑、白兩色可供選擇。',
            'faqs' => $faqs
        ], 'frontend');
    }
    
    /**
     * 技術資料
     */
    public function productsSpecs()
    {
        return $this->view('frontend/products/specs', [
            'title' => '技術資料｜Ascenda 愛升達家用電梯',
            'description' => 'Ascenda 愛升達家用電梯的詳細技術規格與性能資料。'
        ], 'frontend');
    }
    
    /**
     * 產品 Q&A
     */
    public function productsFaq()
    {
        return $this->view('frontend/products/faq', [
            'title' => '產品 Q&A｜Ascenda 愛升達家用電梯',
            'description' => '關於 Ascenda 愛升達家用電梯的常見問題解答。'
        ], 'frontend');
    }
    
    /**
     * 知識分享列表
     */
    public function knowledge()
    {
        // 取得所有已啟用的知識分享
        $knowledgeList = $this->knowledgeModel->getActiveKnowledge(50);
        
        // 取得類別對應表
        $codeDefModel = new CodeDef();
        $categories = $codeDefModel->toSelectOptions('knowledge_category');
        
        return $this->view('frontend/knowledge/index', [
            'title' => '知識分享｜Ascenda 愛升達家用電梯',
            'description' => '探索家用電梯的專業知識與生活美學分享。',
            'knowledgeList' => $knowledgeList,
            'categories' => $categories
        ], 'frontend');
    }
    
    /**
     * 知識分享明細
     */
    public function knowledgeDetail(int $id)
    {
        $knowledge = $this->knowledgeModel->find($id);
        
        // 如果找不到或狀態為停用，導向列表頁
        if (!$knowledge || $knowledge['status'] != 1) {
            header('Location: /knowledge');
            exit;
        }
        
        // 取得類別對應表
        $codeDefModel = new CodeDef();
        $categories = $codeDefModel->toSelectOptions('knowledge_category');
        $categoryName = $categories[$knowledge['category']] ?? $knowledge['category'];
        
        return $this->view('frontend/knowledge/detail', [
            'title' => $knowledge['title'] . '｜知識分享｜Ascenda 愛升達家用電梯',
            'description' => substr(\strip_tags($knowledge['content'] ?? ''), 0, 150),
            'knowledge' => $knowledge,
            'categoryName' => $categoryName
        ], 'frontend');
    }
    
    /**
     * 聯繫我們頁面
     */
    public function contactPage()
    {
        return $this->view('frontend/contact/index', [
            'title' => '聯繫我們｜Ascenda 愛升達家用電梯',
            'description' => '歡迎聯繫鴻展盟，預約參觀展示中心或諮詢 Ascenda 愛升達家用電梯相關資訊。'
        ], 'frontend');
    }
    
    /**
     * 處理聯絡表單
     */
    public function doContact()
    {
        $name = trim($this->input('name', ''));
        $email = trim($this->input('email', ''));
        $phone = trim($this->input('phone', ''));
        $message = trim($this->input('message', ''));
        
        if (empty($name) || empty($email) || empty($message)) {
            return $this->json([
                'success' => false,
                'message' => '請填寫必填欄位'
            ]);
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->json([
                'success' => false,
                'message' => 'Email 格式不正確'
            ]);
        }
        
        // TODO: 儲存聯絡訊息或發送郵件
        
        return $this->json([
            'success' => true,
            'message' => '感謝您的來信，我們將盡快與您聯繫'
        ]);
    }
}
