<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\FunctionModel;
use App\Models\User;

class FunctionController extends Controller
{
    protected $functionModel;
    protected $userModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->functionModel = new FunctionModel();
        $this->userModel = new User();
    }
    
    /**
     * 功能列表
     */
    public function index()
    {
        $functions = $this->functionModel->getTree();
        
        return $this->view('admin/functions/index', [
            'title' => '功能管理',
            'functions' => $functions
        ], 'admin');
    }
    
    /**
     * 新增功能頁面
     */
    public function create()
    {
        $parentFunctions = $this->functionModel->where('parent_id', '=', null)
                                               ->orWhere('parent_id', '=', 0)
                                               ->orderBy('sort_order')
                                               ->get();
        
        return $this->view('admin/functions/create', [
            'title' => '新增功能',
            'parentFunctions' => $parentFunctions
        ], 'admin');
    }
    
    /**
     * 儲存新功能
     */
    public function store()
    {
        if (!$this->verifyCsrf()) {
            return $this->json(['success' => false, 'message' => '安全驗證失敗']);
        }
        
        $name = trim($this->input('name', ''));
        $code = trim($this->input('code', ''));
        $parentId = $this->input('parent_id', null);
        $url = trim($this->input('url', ''));
        $icon = trim($this->input('icon', ''));
        $sortOrder = (int)$this->input('sort_order', 0);
        $isMenu = (int)$this->input('is_menu', 1);
        
        // 驗證
        if (empty($name)) {
            return $this->json(['success' => false, 'message' => '請輸入功能名稱']);
        }
        
        if (empty($code)) {
            return $this->json(['success' => false, 'message' => '請輸入功能代碼']);
        }
        
        if (!preg_match('/^[a-z0-9_\.]+$/', $code)) {
            return $this->json(['success' => false, 'message' => '功能代碼只能包含小寫英文、數字、底線和點']);
        }
        
        if ($this->functionModel->findByCode($code)) {
            return $this->json(['success' => false, 'message' => '功能代碼已存在']);
        }
        
        // 建立功能
        $functionId = $this->functionModel->create([
            'name' => $name,
            'code' => $code,
            'parent_id' => $parentId ?: null,
            'url' => $url,
            'icon' => $icon,
            'sort_order' => $sortOrder,
            'is_menu' => $isMenu,
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        return $this->json([
            'success' => true,
            'message' => '功能建立成功',
            'redirect' => '/admin/functions'
        ]);
    }
    
    /**
     * 編輯功能頁面
     */
    public function edit($id)
    {
        $function = $this->functionModel->find($id);
        
        if (!$function) {
            return $this->redirect('/admin/functions');
        }
        
        $parentFunctions = $this->functionModel->where('parent_id', '=', null)
                                               ->orWhere('parent_id', '=', 0)
                                               ->where('id', '!=', $id)
                                               ->orderBy('sort_order')
                                               ->get();
        
        return $this->view('admin/functions/edit', [
            'title' => '編輯功能',
            'function' => $function,
            'parentFunctions' => $parentFunctions
        ], 'admin');
    }
    
    /**
     * 更新功能
     */
    public function update($id)
    {
        if (!$this->verifyCsrf()) {
            return $this->json(['success' => false, 'message' => '安全驗證失敗']);
        }
        
        $function = $this->functionModel->find($id);
        
        if (!$function) {
            return $this->json(['success' => false, 'message' => '功能不存在']);
        }
        
        $name = trim($this->input('name', ''));
        $code = trim($this->input('code', ''));
        $parentId = $this->input('parent_id', null);
        $url = trim($this->input('url', ''));
        $icon = trim($this->input('icon', ''));
        $sortOrder = (int)$this->input('sort_order', 0);
        $isMenu = (int)$this->input('is_menu', 1);
        
        // 驗證
        if (empty($name)) {
            return $this->json(['success' => false, 'message' => '請輸入功能名稱']);
        }
        
        if (empty($code)) {
            return $this->json(['success' => false, 'message' => '請輸入功能代碼']);
        }
        
        $existingFunction = $this->functionModel->findByCode($code);
        if ($existingFunction && $existingFunction['id'] != $id) {
            return $this->json(['success' => false, 'message' => '功能代碼已存在']);
        }
        
        // 不能將自己設為自己的父級
        if ($parentId == $id) {
            return $this->json(['success' => false, 'message' => '不能將自己設為父功能']);
        }
        
        // 更新功能
        $this->functionModel->update($id, [
            'name' => $name,
            'code' => $code,
            'parent_id' => $parentId ?: null,
            'url' => $url,
            'icon' => $icon,
            'sort_order' => $sortOrder,
            'is_menu' => $isMenu,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        return $this->json([
            'success' => true,
            'message' => '功能更新成功'
        ]);
    }
    
    /**
     * 刪除功能
     */
    public function delete($id)
    {
        if (!$this->verifyCsrf()) {
            return $this->json(['success' => false, 'message' => '安全驗證失敗']);
        }
        
        $function = $this->functionModel->find($id);
        
        if (!$function) {
            return $this->json(['success' => false, 'message' => '功能不存在']);
        }
        
        // 檢查是否有子功能
        $childrenCount = $this->functionModel->where('parent_id', '=', $id)->count();
        if ($childrenCount > 0) {
            return $this->json(['success' => false, 'message' => '此功能有子功能，請先刪除子功能']);
        }
        
        // 刪除功能 (軟刪除)
        $this->functionModel->update($id, [
            'status' => 0,
            'deleted_at' => date('Y-m-d H:i:s')
        ]);
        
        return $this->json([
            'success' => true,
            'message' => '功能已刪除'
        ]);
    }
    
    /**
     * 更新排序
     */
    public function updateSort()
    {
        if (!$this->verifyCsrf()) {
            return $this->json(['success' => false, 'message' => '安全驗證失敗']);
        }
        
        $sortData = $this->input('sort', []);
        
        foreach ($sortData as $item) {
            $this->functionModel->update($item['id'], [
                'sort_order' => (int)$item['sort_order'],
                'parent_id' => $item['parent_id'] ?: null
            ]);
        }
        
        return $this->json([
            'success' => true,
            'message' => '排序已更新'
        ]);
    }
}
