<?php

declare(strict_types=1);

require_once __DIR__ . '/../../bootstrap.php';

try {
    requireRole(STAFF_ROLES);

    $file = $_FILES['file'] ?? null;
    if (!is_array($file) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        throw new InvalidArgumentException('Chưa chọn được file ảnh để tải lên.');
    }

    if ((int) ($file['size'] ?? 0) > 5 * 1024 * 1024) {
        throw new InvalidArgumentException('Ảnh vượt quá 5MB.');
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = (string) $finfo->file((string) $file['tmp_name']);
    $extensions = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
    ];
    if (!isset($extensions[$mime])) {
        throw new InvalidArgumentException('Chỉ nhận ảnh JPG, PNG, GIF hoặc WEBP.');
    }

    $directory = PROJECT_PATH . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'news';
    if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
        throw new RuntimeException('Không tạo được thư mục lưu ảnh.');
    }

    $name = 'news-' . date('Ymd') . '-' . bin2hex(random_bytes(4)) . '.' . $extensions[$mime];
    if (!move_uploaded_file((string) $file['tmp_name'], $directory . DIRECTORY_SEPARATOR . $name)) {
        throw new RuntimeException('Không lưu được file ảnh tải lên.');
    }

    // Đường dẫn tương đối từ gốc site, cùng quy ước với ảnh sản phẩm (assets/products/...).
    jsonResponse([
        'ok' => true,
        'message' => 'Ảnh đã được tải lên.',
        'data' => [
            'url' => 'assets/news/' . $name,
            'name' => $name,
        ],
    ]);
} catch (Throwable $exception) {
    apiError($exception);
}
