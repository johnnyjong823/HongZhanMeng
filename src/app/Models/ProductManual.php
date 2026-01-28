<?php
namespace App\Models;

use Core\Model;

class ProductManual extends Model
{
    protected $table = 'productmanuals';
    protected $fillable = [
        'product_id',
        'title',
        'file_path',
        'filename',
        'file_type',
        'file_size',
        'sort_order',
        'status',
        'created_by'
    ];
    
    /**
     * 取得產品的所有手冊
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
     * 刪除手冊（含檔案）
     */
    public function deleteWithFile($id)
    {
        $manual = $this->find($id);
        
        if (!$manual) {
            return false;
        }
        
        // 刪除實際檔案
        if (!empty($manual['file_path'])) {
            $filePath = ROOT_PATH . '/public' . $manual['file_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        return $this->delete($id);
    }
    
    /**
     * 刪除產品的所有手冊
     */
    public function deleteAllByProductId($productId)
    {
        $manuals = $this->getByProductId($productId);
        
        foreach ($manuals as $manual) {
            $this->deleteWithFile($manual['id']);
        }
        
        return true;
    }
    
    /**
     * 更新排序
     */
    public function updateSortOrder($id, $sortOrder)
    {
        return $this->update($id, ['sort_order' => (int)$sortOrder]);
    }
    
    /**
     * 取得檔案類型圖示
     */
    public static function getFileIcon($fileType)
    {
        $icons = [
            'pdf' => 'fa-file-pdf',
            'doc' => 'fa-file-word',
            'docx' => 'fa-file-word',
            'xls' => 'fa-file-excel',
            'xlsx' => 'fa-file-excel',
            'ppt' => 'fa-file-powerpoint',
            'pptx' => 'fa-file-powerpoint',
        ];
        
        return $icons[$fileType] ?? 'fa-file';
    }
    
    /**
     * 格式化檔案大小
     */
    public static function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}
