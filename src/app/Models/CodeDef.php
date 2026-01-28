<?php
namespace App\Models;

use Core\Model;

class CodeDef extends Model
{
    protected $table = 'codedef';
    protected $fillable = [
        'code_type',
        'code_id',
        'code_name',
        'code_value',
        'sort_order',
        'status',
        'remark'
    ];
    
    /**
     * 依類型取得參數列表
     * 
     * @param string $codeType 參數類型
     * @param bool $onlyActive 是否只取啟用的
     * @return array
     */
    public function getByType($codeType, $onlyActive = true)
    {
        $sql = "SELECT * FROM {$this->table} WHERE code_type = :code_type";
        $params = ['code_type' => $codeType];
        
        if ($onlyActive) {
            $sql .= " AND status = 1";
        }
        
        $sql .= " ORDER BY sort_order, id";
        
        return $this->db->query($sql, $params)->fetchAll();
    }
    
    /**
     * 依類型和代碼取得單一參數
     * 
     * @param string $codeType 參數類型
     * @param string $codeId 參數代碼
     * @return array|null
     */
    public function getByTypeAndId($codeType, $codeId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE code_type = :code_type AND code_id = :code_id LIMIT 1";
        return $this->db->query($sql, [
            'code_type' => $codeType,
            'code_id' => $codeId
        ])->fetch();
    }
    
    /**
     * 取得參數名稱
     * 
     * @param string $codeType 參數類型
     * @param string $codeId 參數代碼
     * @return string|null
     */
    public function getName($codeType, $codeId)
    {
        $item = $this->getByTypeAndId($codeType, $codeId);
        return $item['code_name'] ?? null;
    }
    
    /**
     * 取得參數值
     * 
     * @param string $codeType 參數類型
     * @param string $codeId 參數代碼
     * @return string|null
     */
    public function getValue($codeType, $codeId)
    {
        $item = $this->getByTypeAndId($codeType, $codeId);
        return $item['code_value'] ?? null;
    }
    
    /**
     * 取得所有類型
     * 
     * @return array
     */
    public function getTypes()
    {
        $sql = "SELECT DISTINCT code_type FROM {$this->table} ORDER BY code_type";
        return $this->db->query($sql)->fetchAll(\PDO::FETCH_COLUMN);
    }
    
    /**
     * 轉換為下拉選單格式 (id => name)
     * 
     * @param string $codeType 參數類型
     * @param bool $onlyActive 是否只取啟用的
     * @return array
     */
    public function toSelectOptions($codeType, $onlyActive = true)
    {
        $items = $this->getByType($codeType, $onlyActive);
        $options = [];
        
        foreach ($items as $item) {
            $options[$item['code_id']] = $item['code_name'];
        }
        
        return $options;
    }
    
    /**
     * 檢查代碼是否存在
     * 
     * @param string $codeType 參數類型
     * @param string $codeId 參數代碼
     * @param int|null $excludeId 排除的 ID（用於更新時）
     * @return bool
     */
    public function codeExists($codeType, $codeId, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE code_type = :code_type AND code_id = :code_id";
        $params = [
            'code_type' => $codeType,
            'code_id' => $codeId
        ];
        
        if ($excludeId) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = $excludeId;
        }
        
        $result = $this->db->query($sql, $params)->fetch();
        return $result['count'] > 0;
    }
    
    // ========================================
    // 常用參數類型常數
    // ========================================
    
    const TYPE_PRODUCT_CATEGORY = 'product_category';
    const TYPE_CIBES_CATEGORY = 'cibes_category';
    // const TYPE_ORDER_STATUS = 'order_status';
    // const TYPE_PAYMENT_METHOD = 'payment_method';
    
    /**
     * 取得產品類別列表
     */
    public function getProductCategories($onlyActive = true)
    {
        return $this->getByType(self::TYPE_PRODUCT_CATEGORY, $onlyActive);
    }
    
    /**
     * 取得產品類別下拉選項
     */
    public function getProductCategoryOptions()
    {
        return $this->toSelectOptions(self::TYPE_PRODUCT_CATEGORY);
    }
    
    /**
     * 取得 Cibes 類別列表
     */
    public function getCibesCategories($onlyActive = true)
    {
        return $this->getByType(self::TYPE_CIBES_CATEGORY, $onlyActive);
    }
    
    /**
     * 取得 Cibes 類別下拉選項
     */
    public function getCibesCategoryOptions()
    {
        return $this->toSelectOptions(self::TYPE_CIBES_CATEGORY);
    }
}
