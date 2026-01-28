<?php
namespace Core;

class View
{
    protected $data = [];
    protected $layout = null;
    
    /**
     * 渲染視圖
     */
    public function render($template, $data = [], $layout = null)
    {
        $this->data = $data;
        $this->layout = $layout;
        
        // 加入全域資料
        $this->data['flash'] = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        
        $this->data['currentUser'] = $_SESSION['user'] ?? null;
        $this->data['menu'] = $_SESSION['menu'] ?? [];
        
        // 取得視圖內容
        $content = $this->getViewContent($template);
        
        // 如果有版型，將內容嵌入版型
        if ($this->layout) {
            $this->data['content'] = $content;
            echo $this->getLayoutContent($this->layout);
        } else {
            echo $content;
        }
    }
    
    /**
     * 取得視圖內容
     */
    protected function getViewContent($template)
    {
        $viewPath = ROOT_PATH . '/app/Views/' . $template . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \Exception("View {$template} not found");
        }
        
        extract($this->data);
        
        ob_start();
        include $viewPath;
        return ob_get_clean();
    }
    
    /**
     * 取得版型內容
     */
    protected function getLayoutContent($layout)
    {
        $layoutPath = ROOT_PATH . '/app/Views/layouts/' . $layout . '.php';
        
        if (!file_exists($layoutPath)) {
            throw new \Exception("Layout {$layout} not found");
        }
        
        extract($this->data);
        
        ob_start();
        include $layoutPath;
        return ob_get_clean();
    }
    
    /**
     * 載入部分視圖
     */
    public static function partial($name, $data = [])
    {
        $path = ROOT_PATH . '/app/Views/' . $name . '.php';
        
        if (file_exists($path)) {
            extract($data);
            include $path;
        }
    }
    
    /**
     * 輸出 HTML 轉義字串
     */
    public static function e($string)
    {
        return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
    }
}
