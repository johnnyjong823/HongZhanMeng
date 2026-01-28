<?php
namespace Core;

abstract class Model
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * 取得所有資料
     */
    public function all($columns = ['*'], $orderBy = null)
    {
        $cols = implode(', ', $columns);
        $sql = "SELECT {$cols} FROM {$this->table}";
        
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        
        return $this->db->query($sql)->fetchAll();
    }
    
    /**
     * 依 ID 查詢
     */
    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1";
        return $this->db->query($sql, ['id' => $id])->fetch();
    }
    
    /**
     * 依條件查詢多筆
     */
    public function where($column, $value, $operator = '=')
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} {$operator} :value";
        return $this->db->query($sql, ['value' => $value])->fetchAll();
    }
    
    /**
     * 依條件查詢單筆
     */
    public function findBy($column, $value)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = :value LIMIT 1";
        return $this->db->query($sql, ['value' => $value])->fetch();
    }
    
    /**
     * 新增資料
     */
    public function create(array $data)
    {
        $data = $this->filterFillable($data);
        
        if (empty($data)) {
            return false;
        }
        
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $this->db->query($sql, $data);
        
        return $this->db->lastInsertId();
    }
    
    /**
     * 更新資料
     */
    public function update($id, array $data)
    {
        $data = $this->filterFillable($data);
        
        if (empty($data)) {
            return false;
        }
        
        $sets = [];
        foreach (array_keys($data) as $column) {
            $sets[] = "{$column} = :{$column}";
        }
        
        $setString = implode(', ', $sets);
        $data['id'] = $id;
        
        $sql = "UPDATE {$this->table} SET {$setString} WHERE {$this->primaryKey} = :id";
        return $this->db->query($sql, $data)->rowCount();
    }
    
    /**
     * 刪除資料
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        return $this->db->query($sql, ['id' => $id])->rowCount();
    }
    
    /**
     * 計算數量
     */
    public function count($conditions = [])
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        
        if (!empty($conditions)) {
            $where = [];
            foreach (array_keys($conditions) as $column) {
                $where[] = "{$column} = :{$column}";
            }
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        
        $result = $this->db->query($sql, $conditions)->fetch();
        return $result['count'] ?? 0;
    }
    
    /**
     * 過濾可填入欄位
     */
    protected function filterFillable(array $data)
    {
        if (empty($this->fillable)) {
            return $data;
        }
        
        return array_intersect_key($data, array_flip($this->fillable));
    }
    
    /**
     * 分頁查詢
     */
    public function paginate($page = 1, $perPage = 20, $conditions = [], $orderBy = 'id DESC')
    {
        $page = max(1, (int) $page);
        $offset = ($page - 1) * $perPage;
        
        // 建立 WHERE 子句
        $whereClauses = [];
        $params = [];
        
        foreach ($conditions as $column => $value) {
            if (is_array($value)) {
                // 支援 ['column' => ['operator' => '>', 'value' => 10]]
                $operator = $value['operator'] ?? '=';
                $whereClauses[] = "{$column} {$operator} :{$column}";
                $params[$column] = $value['value'];
            } else {
                $whereClauses[] = "{$column} = :{$column}";
                $params[$column] = $value;
            }
        }
        
        $whereClause = !empty($whereClauses) ? 'WHERE ' . implode(' AND ', $whereClauses) : '';
        
        // 計算總數
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} {$whereClause}";
        $total = $this->db->query($countSql, $params)->fetch()['total'];
        
        // 查詢資料
        $sql = "SELECT * FROM {$this->table} {$whereClause} ORDER BY {$orderBy} LIMIT {$perPage} OFFSET {$offset}";
        $data = $this->db->query($sql, $params)->fetchAll();
        
        $lastPage = ceil($total / $perPage);
        
        return [
            'data' => $data,
            'total' => (int) $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => max(1, $lastPage),
            'from' => $total > 0 ? $offset + 1 : 0,
            'to' => min($offset + $perPage, $total),
        ];
    }
    
    /**
     * 執行原生 SQL
     */
    public function raw($sql, $params = [])
    {
        return $this->db->query($sql, $params);
    }
    
    /**
     * 取得資料庫實體
     */
    protected function getDb()
    {
        return $this->db;
    }
}
