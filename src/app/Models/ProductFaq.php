<?php
namespace App\Models;

use Core\Model;

class ProductFaq extends Model
{
    protected $table = 'productfaqs';
    protected $fillable = [
        'product_id',
        'question',
        'answer',
        'sort_order',
        'status',
        'created_by',
        'updated_by'
    ];
    
    /**
     * 取得產品的所有 FAQ
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
    
    /**
     * 切換狀態
     */
    public function toggleStatus($id)
    {
        $faq = $this->find($id);
        if (!$faq) {
            return false;
        }
        
        $newStatus = $faq['status'] == 1 ? 0 : 1;
        return $this->update($id, ['status' => $newStatus]);
    }
}
