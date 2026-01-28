<?php
namespace App\Models;

use Core\Model;

class ProductDetail extends Model
{
    protected $table = 'productdetails';
    protected $fillable = [
        'product_id',
        'title',
        'content',
        'image_path',
        'image_filename',
        'sort_order',
        'status',
        'created_by',
        'updated_by'
    ];
    
    /**
     * 取得產品的所有明細
     */
    public function getByProductId($productId, $onlyActive = false)
    {
        $sql = "SELECT * FROM {$this->table} WHERE product_id = :product_id";
        
        if ($onlyActive) {
            $sql .= " AND status = 1";
        }
        
        $sql .= " ORDER BY sort_order, id";
        
        return $this->db->query($sql, ['product_id' => $productId])->fetchAll();
    }
    
    /**
     * 刪除明細（含圖片檔案）
     */
    public function deleteWithFile($id)
    {
        $detail = $this->find($id);
        
        if (!$detail) {
            return false;
        }
        
        // 刪除圖片檔案
        if (!empty($detail['image_path'])) {
            $filePath = ROOT_PATH . '/public' . $detail['image_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        return $this->delete($id);
    }
    
    /**
     * 更新排序
     */
    public function updateSortOrder($id, $sortOrder)
    {
        return $this->update($id, ['sort_order' => (int)$sortOrder]);
    }
    
    /**
     * 批次更新排序
     */
    public function batchUpdateSortOrder(array $orders)
    {
        foreach ($orders as $id => $sortOrder) {
            $this->updateSortOrder($id, $sortOrder);
        }
        return true;
    }
}
