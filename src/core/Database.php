<?php
namespace Core;

class Database
{
    private static $instance = null;
    private $pdo;
    
    private function __construct()
    {
        $config = require ROOT_PATH . '/config/database.php';
        
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $config['host'],
            $config['port'],
            $config['database'],
            $config['charset']
        );
        
        $this->pdo = new \PDO(
            $dsn,
            $config['username'],
            $config['password'],
            $config['options'] ?? []
        );
    }
    
    /**
     * 禁止複製
     */
    private function __clone() {}
    
    /**
     * 取得單例實體
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    /**
     * 執行查詢
     */
    public function query($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    /**
     * 取得最後插入 ID
     */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
    
    /**
     * 開始交易
     */
    public function beginTransaction()
    {
        return $this->pdo->beginTransaction();
    }
    
    /**
     * 提交交易
     */
    public function commit()
    {
        return $this->pdo->commit();
    }
    
    /**
     * 回滾交易
     */
    public function rollBack()
    {
        return $this->pdo->rollBack();
    }
    
    /**
     * 取得 PDO 實體
     */
    public function getPdo()
    {
        return $this->pdo;
    }
}
