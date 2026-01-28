<?php
/**
 * Banner Model
 * 圖片輪播模型
 */

namespace App\Models;

use Core\Model;

class Banner extends Model
{
    protected $table = 'banners';
    protected $primaryKey = 'id';
    
    /**
     * 輪播位置常數
     */
    const POSITION_HERO = 'hero';        // 首頁主輪播
    const POSITION_FEATURES = 'features'; // 下方三圖輪播
    
    /**
     * 媒體類型常數
     */
    const MEDIA_IMAGE = 'image';
    const MEDIA_VIDEO = 'video';
    
    /**
     * 取得位置選項
     */
    public static function getPositionOptions(): array
    {
        return [
            self::POSITION_HERO => '首頁主輪播（支援圖片/影片）',
            self::POSITION_FEATURES => '下方三圖輪播（圖片+文字）'
        ];
    }
    
    /**
     * 取得媒體類型選項
     */
    public static function getMediaTypeOptions(): array
    {
        return [
            self::MEDIA_IMAGE => '圖片',
            self::MEDIA_VIDEO => '影片'
        ];
    }
    
    /**
     * 取得所有輪播圖片（含分頁）
     */
    public function search(array $filters = [], int $page = 1, int $perPage = 15): array
    {
        $where = [];
        $params = [];
        
        // 位置篩選
        if (!empty($filters['position'])) {
            $where[] = 'position = :position';
            $params['position'] = $filters['position'];
        }
        
        // 狀態篩選
        if (isset($filters['status']) && $filters['status'] !== '') {
            $where[] = 'status = :status';
            $params['status'] = (int)$filters['status'];
        }
        
        // 關鍵字搜尋
        if (!empty($filters['search'])) {
            $where[] = '(title LIKE :search OR description LIKE :search)';
            $params['search'] = '%' . $filters['search'] . '%';
        }
        
        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
        
        // 計算總數
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} {$whereClause}";
        $countResult = $this->db->query($countSql, $params)->fetch();
        $total = $countResult['total'];
        
        // 計算分頁
        $totalPages = ceil($total / $perPage);
        $offset = ($page - 1) * $perPage;
        
        // 查詢資料
        $sql = "SELECT * FROM {$this->table} {$whereClause} ORDER BY position ASC, sort_order ASC, id DESC LIMIT {$perPage} OFFSET {$offset}";
        $banners = $this->db->query($sql, $params)->fetchAll();
        
        return [
            'data' => $banners,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'total_pages' => $totalPages
            ]
        ];
    }
    
    /**
     * 依位置取得輪播
     */
    public function getByPosition(string $position, bool $activeOnly = true): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE position = :position";
        $params = ['position' => $position];
        
        if ($activeOnly) {
            $sql .= " AND status = 1 
                      AND (start_date IS NULL OR start_date <= CURDATE())
                      AND (end_date IS NULL OR end_date >= CURDATE())";
        }
        
        $sql .= " ORDER BY sort_order ASC, id DESC";
        
        return $this->db->query($sql, $params)->fetchAll();
    }
    
    /**
     * 取得首頁主輪播
     */
    public function getHeroBanners(): array
    {
        return $this->getByPosition(self::POSITION_HERO);
    }
    
    /**
     * 取得下方三圖輪播
     */
    public function getFeaturesBanners(): array
    {
        return $this->getByPosition(self::POSITION_FEATURES);
    }
    
    /**
     * 取得所有輪播圖片（不分頁，用於排序）
     */
    public function getAll(bool $activeOnly = false): array
    {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];
        
        if ($activeOnly) {
            $sql .= " WHERE status = 1 
                      AND (start_date IS NULL OR start_date <= CURDATE())
                      AND (end_date IS NULL OR end_date >= CURDATE())";
        }
        
        $sql .= " ORDER BY position ASC, sort_order ASC, id DESC";
        
        return $this->db->query($sql, $params)->fetchAll();
    }
    
    /**
     * 取得前台顯示的輪播圖片
     */
    public function getActiveBanners(int $limit = 10): array
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE status = 1 
                AND (start_date IS NULL OR start_date <= CURDATE())
                AND (end_date IS NULL OR end_date >= CURDATE())
                ORDER BY position ASC, sort_order ASC, id DESC 
                LIMIT :limit";
        
        return $this->db->query($sql, ['limit' => $limit])->fetchAll();
    }
    
    /**
     * 建立輪播圖片
     */
    public function create(array $data)
    {
        $sql = "INSERT INTO {$this->table} 
                (position, media_type, title, description, image_path, video_path, link_url, link_target, sort_order, status, start_date, end_date) 
                VALUES 
                (:position, :media_type, :title, :description, :image_path, :video_path, :link_url, :link_target, :sort_order, :status, :start_date, :end_date)";
        
        $params = [
            'position' => $data['position'] ?? self::POSITION_HERO,
            'media_type' => $data['media_type'] ?? self::MEDIA_IMAGE,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'image_path' => $data['image_path'] ?? null,
            'video_path' => $data['video_path'] ?? null,
            'link_url' => $data['link_url'] ?? null,
            'link_target' => $data['link_target'] ?? '_self',
            'sort_order' => $data['sort_order'] ?? $this->getNextSortOrder($data['position'] ?? self::POSITION_HERO),
            'status' => $data['status'] ?? 1,
            'start_date' => !empty($data['start_date']) ? $data['start_date'] : null,
            'end_date' => !empty($data['end_date']) ? $data['end_date'] : null
        ];
        
        $result = $this->db->query($sql, $params);
        return $result ? $this->db->lastInsertId() : false;
    }
    
    /**
     * 更新輪播圖片
     */
    public function update($id, array $data)
    {
        $fields = [];
        $params = ['id' => $id];
        
        $allowedFields = ['position', 'media_type', 'title', 'description', 'image_path', 'video_path', 'link_url', 'link_target', 'sort_order', 'status', 'start_date', 'end_date'];
        
        foreach ($allowedFields as $field) {
            if (array_key_exists($field, $data)) {
                if (in_array($field, ['start_date', 'end_date'])) {
                    $fields[] = "{$field} = :{$field}";
                    $params[$field] = !empty($data[$field]) ? $data[$field] : null;
                } else {
                    $fields[] = "{$field} = :{$field}";
                    $params[$field] = $data[$field];
                }
            }
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
        return $this->db->query($sql, $params) !== false;
    }
    
    /**
     * 刪除輪播圖片
     */
    public function delete($id)
    {
        // 先取得圖片/影片路徑
        $banner = $this->find($id);
        if (!$banner) {
            return false;
        }
        
        // 刪除圖片檔案
        if (!empty($banner['image_path'])) {
            $filePath = ROOT_PATH . '/public' . $banner['image_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        // 刪除影片檔案
        if (!empty($banner['video_path'])) {
            $filePath = ROOT_PATH . '/public' . $banner['video_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        // 刪除資料庫記錄
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->db->query($sql, ['id' => $id]) !== false;
    }
    
    /**
     * 更新排序
     */
    public function updateSortOrder(array $orders): bool
    {
        foreach ($orders as $id => $order) {
            $sql = "UPDATE {$this->table} SET sort_order = :sort_order WHERE id = :id";
            $this->db->query($sql, ['id' => $id, 'sort_order' => $order]);
        }
        return true;
    }
    
    /**
     * 批次更新排序
     */
    public function updateSortOrders(array $items): bool
    {
        foreach ($items as $item) {
            if (isset($item['id']) && isset($item['sort_order'])) {
                $this->update((int)$item['id'], ['sort_order' => (int)$item['sort_order']]);
            }
        }
        return true;
    }
    
    /**
     * 取得下一個排序數字
     */
    public function getNextSortOrder(?string $position = null): int
    {
        $sql = "SELECT MAX(sort_order) as max_order FROM {$this->table}";
        $params = [];
        
        if ($position) {
            $sql .= " WHERE position = :position";
            $params['position'] = $position;
        }
        
        $result = $this->db->query($sql, $params)->fetch();
        return ($result['max_order'] ?? 0) + 1;
    }
    
    /**
     * 切換狀態
     */
    public function toggleStatus($id)
    {
        $sql = "UPDATE {$this->table} SET status = IF(status = 1, 0, 1) WHERE id = :id";
        return $this->db->query($sql, ['id' => $id]) !== false;
    }
    
    /**
     * 上傳並壓縮圖片
     * @param array $file 上傳的檔案
     * @param int $maxWidth 最大寬度（預設 1920px）
     * @param int $quality 壓縮品質（1-100，預設 85）
     */
    public function uploadImage(array $file, int $maxWidth = 1920, int $quality = 85)
    {
        // 驗證檔案
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 10 * 1024 * 1024; // 允許上傳 10MB（壓縮前）
        
        if (!in_array($file['type'], $allowedTypes)) {
            return false;
        }
        
        if ($file['size'] > $maxSize) {
            return false;
        }
        
        // 建立上傳目錄
        $uploadDir = ROOT_PATH . '/public/uploads/banners';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // 生成檔名（統一轉為 webp 或 jpg 格式以獲得更好的壓縮率）
        $outputFormat = $this->determineOutputFormat($file['type']);
        $filename = 'banner_' . date('YmdHis') . '_' . uniqid() . '.' . $outputFormat;
        $filepath = $uploadDir . '/' . $filename;
        
        // 壓縮並儲存圖片
        if ($this->compressImage($file['tmp_name'], $filepath, $file['type'], $maxWidth, $quality)) {
            return '/uploads/banners/' . $filename;
        }
        
        return false;
    }
    
    /**
     * 決定輸出格式
     */
    private function determineOutputFormat(string $mimeType): string
    {
        // GIF 保持原格式（支援動畫）
        if ($mimeType === 'image/gif') {
            return 'gif';
        }
        
        // 若系統支援 WebP，優先使用 WebP（最佳壓縮率）
        if (\function_exists('imagewebp')) {
            return 'webp';
        }
        
        // 否則使用 JPEG
        return 'jpg';
    }
    
    /**
     * 壓縮圖片
     */
    private function compressImage(string $source, string $destination, string $mimeType, int $maxWidth, int $quality): bool
    {
        // 取得原圖資訊
        $imageInfo = \getimagesize($source);
        if (!$imageInfo) {
            return false;
        }
        
        $originalWidth = $imageInfo[0];
        $originalHeight = $imageInfo[1];
        
        // 建立圖片資源
        $sourceImage = $this->createImageFromFile($source, $mimeType);
        if (!$sourceImage) {
            return false;
        }
        
        // 計算新尺寸
        $newWidth = $originalWidth;
        $newHeight = $originalHeight;
        
        if ($originalWidth > $maxWidth) {
            $ratio = $maxWidth / $originalWidth;
            $newWidth = $maxWidth;
            $newHeight = (int)($originalHeight * $ratio);
        }
        
        // 建立新圖片
        $newImage = \imagecreatetruecolor($newWidth, $newHeight);
        
        // 處理透明背景（PNG、WebP、GIF）
        if (\in_array($mimeType, ['image/png', 'image/webp', 'image/gif'])) {
            \imagealphablending($newImage, false);
            \imagesavealpha($newImage, true);
            $transparent = \imagecolorallocatealpha($newImage, 0, 0, 0, 127);
            \imagefill($newImage, 0, 0, $transparent);
        }
        
        // 縮放圖片
        \imagecopyresampled(
            $newImage, $sourceImage,
            0, 0, 0, 0,
            $newWidth, $newHeight,
            $originalWidth, $originalHeight
        );
        
        // 儲存壓縮後的圖片
        $result = $this->saveCompressedImage($newImage, $destination, $quality);
        
        // 釋放記憶體
        \imagedestroy($sourceImage);
        \imagedestroy($newImage);
        
        return $result;
    }
    
    /**
     * 從檔案建立圖片資源
     */
    private function createImageFromFile(string $filepath, string $mimeType)
    {
        switch ($mimeType) {
            case 'image/jpeg':
                return \imagecreatefromjpeg($filepath);
            case 'image/png':
                return \imagecreatefrompng($filepath);
            case 'image/gif':
                return \imagecreatefromgif($filepath);
            case 'image/webp':
                return \imagecreatefromwebp($filepath);
            default:
                return false;
        }
    }
    
    /**
     * 儲存壓縮後的圖片
     */
    private function saveCompressedImage($image, string $destination, int $quality): bool
    {
        $extension = \strtolower(\pathinfo($destination, PATHINFO_EXTENSION));
        
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                return \imagejpeg($image, $destination, $quality);
            case 'png':
                // PNG 品質 0-9，需要轉換
                $pngQuality = (int)(9 - ($quality / 100 * 9));
                return \imagepng($image, $destination, $pngQuality);
            case 'gif':
                return \imagegif($image, $destination);
            case 'webp':
                return \imagewebp($image, $destination, $quality);
            default:
                return \imagejpeg($image, $destination, $quality);
        }
    }
    
    /**
     * 上傳影片
     * @param array $file 上傳的檔案
     * @param bool $compress 是否嘗試壓縮（需要 FFmpeg，cPanel 通常無法使用）
     */
    public function uploadVideo(array $file, bool $compress = true)
    {
        // 驗證檔案
        $allowedTypes = ['video/mp4', 'video/webm', 'video/ogg'];
        $maxSize = 50 * 1024 * 1024; // 50MB（適用 cPanel 環境）
        
        if (!in_array($file['type'], $allowedTypes)) {
            return false;
        }
        
        if ($file['size'] > $maxSize) {
            return false;
        }
        
        // 建立上傳目錄
        $uploadDir = ROOT_PATH . '/public/uploads/banners/videos';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // 生成檔名
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = 'video_' . date('YmdHis') . '_' . uniqid() . '.' . $extension;
        $filepath = $uploadDir . '/' . $filename;
        
        // 嘗試使用 FFmpeg 壓縮（如果可用）
        if ($compress && $this->isFFmpegAvailable()) {
            // 先移動到暫存位置
            $tempPath = $uploadDir . '/temp_' . uniqid() . '.' . $extension;
            
            if (move_uploaded_file($file['tmp_name'], $tempPath)) {
                $mp4Filename = 'video_' . date('YmdHis') . '_' . uniqid() . '.mp4';
                $mp4Filepath = $uploadDir . '/' . $mp4Filename;
                
                $compressed = $this->compressVideo($tempPath, $mp4Filepath);
                
                // 刪除暫存檔
                if (file_exists($tempPath)) {
                    unlink($tempPath);
                }
                
                if ($compressed) {
                    return '/uploads/banners/videos/' . $mp4Filename;
                }
            }
        }
        
        // 直接儲存原始檔案（cPanel 環境或壓縮失敗時）
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return '/uploads/banners/videos/' . $filename;
        }
        
        return false;
    }
    
    /**
     * 檢查 FFmpeg 是否可用
     */
    private function isFFmpegAvailable(): bool
    {
        // Windows
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            exec('where ffmpeg 2>nul', $output, $returnCode);
        } else {
            // Linux/Mac
            exec('which ffmpeg 2>/dev/null', $output, $returnCode);
        }
        
        return $returnCode === 0;
    }
    
    /**
     * 使用 FFmpeg 壓縮影片
     * @param string $source 來源檔案路徑
     * @param string $destination 目標檔案路徑
     * @param string $preset 壓縮預設（ultrafast, fast, medium, slow）
     * @param int $crf 品質參數（0-51，越低品質越好，建議 23-28）
     */
    private function compressVideo(string $source, string $destination, string $preset = 'medium', int $crf = 28): bool
    {
        // FFmpeg 壓縮指令
        // -i: 輸入檔案
        // -c:v libx264: 使用 H.264 編碼
        // -preset: 編碼速度預設（影響壓縮效率）
        // -crf: 品質控制（23 為預設，28 為較高壓縮）
        // -c:a aac: 音訊使用 AAC 編碼
        // -b:a 128k: 音訊位元率
        // -movflags +faststart: 優化串流播放
        // -y: 覆蓋輸出檔案
        
        $command = sprintf(
            'ffmpeg -i %s -c:v libx264 -preset %s -crf %d -c:a aac -b:a 128k -movflags +faststart -y %s 2>&1',
            escapeshellarg($source),
            escapeshellarg($preset),
            $crf,
            escapeshellarg($destination)
        );
        
        exec($command, $output, $returnCode);
        
        // 檢查輸出檔案是否建立成功
        if ($returnCode === 0 && file_exists($destination) && filesize($destination) > 0) {
            return true;
        }
        
        // 壓縮失敗，記錄錯誤（可選）
        error_log('FFmpeg compression failed: ' . implode("\n", $output));
        
        return false;
    }
    
    /**
     * 取得影片資訊（需要 FFprobe）
     */
    public function getVideoInfo(string $filepath): ?array
    {
        if (!file_exists($filepath)) {
            return null;
        }
        
        $command = sprintf(
            'ffprobe -v quiet -print_format json -show_format -show_streams %s 2>&1',
            escapeshellarg($filepath)
        );
        
        exec($command, $output, $returnCode);
        
        if ($returnCode === 0) {
            $json = implode('', $output);
            $info = json_decode($json, true);
            
            if ($info) {
                return [
                    'duration' => $info['format']['duration'] ?? null,
                    'size' => $info['format']['size'] ?? null,
                    'bitrate' => $info['format']['bit_rate'] ?? null,
                    'width' => $info['streams'][0]['width'] ?? null,
                    'height' => $info['streams'][0]['height'] ?? null,
                ];
            }
        }
        
        return null;
    }
    
    /**
     * 刪除圖片檔案
     */
    public function deleteImageFile($imagePath)
    {
        $filePath = ROOT_PATH . '/public' . $imagePath;
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return false;
    }
    
    /**
     * 刪除影片檔案
     */
    public function deleteVideoFile($videoPath)
    {
        $filePath = ROOT_PATH . '/public' . $videoPath;
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return false;
    }
}
