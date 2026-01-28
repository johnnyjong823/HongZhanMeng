<?php
namespace App\Models;

use Core\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'product_code',
        'slug',
        'product_name',
        'category_id',
        'category_name',
        'size',
        'model',
        'short_description',
        'description',
        'installation',
        'faq',
        'manual_file',
        'manual_filename',
        'status',
        'is_system',
        'sort_order',
        'created_by',
        'updated_by'
    ];
    
    /**
     * 取得所有系統產品（固定產品列表）
     */
    public function getSystemProducts()
    {
        $sql = "SELECT p.*, 
                       (SELECT COUNT(*) FROM productdetails WHERE product_id = p.id) as detail_count,
                       (SELECT COUNT(*) FROM productfaqs WHERE product_id = p.id) as faq_count,
                       (SELECT COUNT(*) FROM productmanuals WHERE product_id = p.id) as manual_count
                FROM {$this->table} p 
                WHERE p.is_system = 1 
                ORDER BY p.sort_order, p.id";
        return $this->db->query($sql)->fetchAll();
    }
    
    /**
     * 依 slug 查詢產品
     */
    public function findBySlug($slug)
    {
        return $this->findBy('slug', $slug);
    }
    
    /**
     * 取得產品完整資料（含明細、FAQ、手冊）
     */
    public function findWithAll($id)
    {
        $product = $this->findWithImages($id);
        
        if ($product) {
            $detailModel = new ProductDetail();
            $faqModel = new ProductFaq();
            $manualModel = new ProductManual();
            
            $product['details'] = $detailModel->getByProductId($id);
            $product['faqs'] = $faqModel->getByProductId($id);
            $product['manuals'] = $manualModel->getByProductId($id);
        }
        
        return $product;
    }
    
    /**
     * 取得產品及其圖片
     */
    public function findWithImages($id)
    {
        $product = $this->find($id);
        
        if ($product) {
            $product['images'] = $this->getImages($id);
        }
        
        return $product;
    }
    
    /**
     * 取得產品所有圖片
     */
    public function getImages($productId)
    {
        $sql = "SELECT * FROM productimages WHERE product_id = :product_id ORDER BY sort_order, id";
        return $this->db->query($sql, ['product_id' => $productId])->fetchAll();
    }
    
    /**
     * 取得主圖
     */
    public function getMainImage($productId)
    {
        $sql = "SELECT * FROM productimages 
                WHERE product_id = :product_id AND image_type = 'main' 
                ORDER BY sort_order LIMIT 1";
        return $this->db->query($sql, ['product_id' => $productId])->fetch();
    }
    
    /**
     * 新增圖片
     */
    public function addImage($productId, $imagePath, $filename = null, $type = 'gallery', $sortOrder = 0)
    {
        $sql = "INSERT INTO productimages (product_id, image_path, image_filename, image_type, sort_order) 
                VALUES (:product_id, :image_path, :image_filename, :image_type, :sort_order)";
        
        $this->db->query($sql, [
            'product_id' => $productId,
            'image_path' => $imagePath,
            'image_filename' => $filename,
            'image_type' => $type,
            'sort_order' => $sortOrder
        ]);
        
        return $this->db->lastInsertId();
    }
    
    /**
     * 刪除圖片
     */
    public function deleteImage($imageId)
    {
        // 先取得圖片資訊
        $sql = "SELECT * FROM productimages WHERE id = :id";
        $image = $this->db->query($sql, ['id' => $imageId])->fetch();
        
        if ($image) {
            // 刪除實際檔案
            $filePath = ROOT_PATH . '/public' . $image['image_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            
            // 刪除資料庫記錄
            $sql = "DELETE FROM productimages WHERE id = :id";
            $this->db->query($sql, ['id' => $imageId]);
        }
        
        return $image;
    }
    
    /**
     * 刪除產品所有圖片
     */
    public function deleteAllImages($productId)
    {
        $images = $this->getImages($productId);
        
        foreach ($images as $image) {
            $filePath = ROOT_PATH . '/public' . $image['image_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        $sql = "DELETE FROM productimages WHERE product_id = :product_id";
        return $this->db->query($sql, ['product_id' => $productId]);
    }
    
    /**
     * 設定主圖
     */
    public function setMainImage($productId, $imageId)
    {
        // 先將所有圖片改為 gallery
        $sql = "UPDATE productimages SET image_type = 'gallery' WHERE product_id = :product_id";
        $this->db->query($sql, ['product_id' => $productId]);
        
        // 再將指定圖片設為主圖
        $sql = "UPDATE productimages SET image_type = 'main' WHERE id = :id";
        return $this->db->query($sql, ['id' => $imageId]);
    }
    
    /**
     * 更新圖片排序
     */
    public function updateImageOrder($imageId, $sortOrder)
    {
        $sql = "UPDATE productimages SET sort_order = :sort_order WHERE id = :id";
        return $this->db->query($sql, ['id' => $imageId, 'sort_order' => $sortOrder]);
    }
    
    /**
     * 取得所有類別 (從 CodeDef 取得)
     */
    public function getCategories($onlyActive = true)
    {
        $codeDefModel = new CodeDef();
        return $codeDefModel->getByType(CodeDef::TYPE_PRODUCT_CATEGORY, $onlyActive);
    }
    
    /**
     * 取得類別名稱
     */
    public function getCategoryName($categoryId)
    {
        $codeDefModel = new CodeDef();
        return $codeDefModel->getName(CodeDef::TYPE_PRODUCT_CATEGORY, $categoryId);
    }
    
    /**
     * 依編號查詢
     */
    public function findByCode($code)
    {
        return $this->findBy('product_code', $code);
    }
    
    /**
     * 分頁查詢 (含搜尋)
     */
    public function search($params = [])
    {
        $page = (int)($params['page'] ?? 1);
        $perPage = (int)($params['per_page'] ?? 20);
        $search = $params['search'] ?? '';
        $categoryId = $params['category_id'] ?? '';
        $status = $params['status'] ?? '';
        
        $offset = ($page - 1) * $perPage;
        
        $whereClauses = ['1=1'];
        $queryParams = [];
        
        if (!empty($search)) {
            $whereClauses[] = "(product_name LIKE :search OR product_code LIKE :search2 OR model LIKE :search3)";
            $queryParams['search'] = "%{$search}%";
            $queryParams['search2'] = "%{$search}%";
            $queryParams['search3'] = "%{$search}%";
        }
        
        if ($categoryId !== '') {
            $whereClauses[] = "category_id = :category_id";
            $queryParams['category_id'] = $categoryId;
        }
        
        if ($status !== '') {
            $whereClauses[] = "status = :status";
            $queryParams['status'] = (int)$status;
        }
        
        $whereClause = implode(' AND ', $whereClauses);
        
        // 計算總數
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} WHERE {$whereClause}";
        $total = $this->db->query($countSql, $queryParams)->fetch()['total'];
        
        // 查詢資料
        $sql = "SELECT * FROM {$this->table} WHERE {$whereClause} ORDER BY sort_order, id DESC LIMIT {$perPage} OFFSET {$offset}";
        $data = $this->db->query($sql, $queryParams)->fetchAll();
        
        // 為每個產品附加主圖
        foreach ($data as &$product) {
            $mainImage = $this->getMainImage($product['id']);
            $product['main_image'] = $mainImage['image_path'] ?? null;
        }
        
        return [
            'data' => $data,
            'total' => (int)$total,
            'per_page' => $perPage,
            'current_page' => $page,
            'total_pages' => max(1, ceil($total / $perPage))
        ];
    }
    
    /**
     * 刪除產品 (含圖片和手冊)
     */
    public function deleteWithFiles($id)
    {
        $product = $this->find($id);
        
        if (!$product) {
            return false;
        }
        
        // 刪除所有圖片
        $this->deleteAllImages($id);
        
        // 刪除手冊檔案
        if (!empty($product['manual_file'])) {
            $filePath = ROOT_PATH . '/public' . $product['manual_file'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        // 刪除產品記錄
        return $this->delete($id);
    }
}
