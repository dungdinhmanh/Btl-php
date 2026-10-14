<?php
declare(strict_types=1);

/**
 * Nối brand với ảnh logo trong assets/img/branding/ rồi ghi vào bảng brand_images.
 * Brand không có file thì BỎ QUA, không ghi dòng — giao diện tự hiện tên brand.
 *
 * Usage: php backend/database/link_brand_images.php [--dry-run] [--check]
 *        --dry-run  chỉ báo cáo, không ghi DB
 *        --check    chỉ kiểm tra bảng đã có khớp với file trên đĩa không
 */

require_once __DIR__ . '/../bootstrap.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

const BRAND_IMAGE_DIR = 'assets/img/branding';
/** Kích thước hiển thị, lấy từ thuộc tính width/height trên <img> của tncstore.vn. */
const LOGO_WIDTH = 50;
const LOGO_HEIGHT = 32;

/** brand_slug KHÔNG khớp tên file trong branding/ — mỗi cặp đã đối chiếu với ảnh thật. */
const LOGO_FILE_OVERRIDE = [
    'lian-li' => 'lianli',
    'cooler-master' => 'coolermaster',
    'human-motion' => 'humanmotion',
];

$dryRun = in_array('--dry-run', array_slice($argv, 1), true);
$checkOnly = in_array('--check', array_slice($argv, 1), true);

$database = database();
$brands = $database->query('SELECT brand_id, brand_name, brand_slug FROM brands ORDER BY brand_id')->fetchAll();
$directory = dirname(__DIR__, 2) . '/' . BRAND_IMAGE_DIR;

$files = [];
foreach (scandir($directory) ?: [] as $entry) {
    if ($entry === '.' || $entry === '..') {
        continue;
    }
    $extension = strtolower(pathinfo($entry, PATHINFO_EXTENSION));
    if (in_array($extension, ['png', 'jpg', 'jpeg', 'webp', 'gif', 'svg'], true)) {
        $files[strtolower(pathinfo($entry, PATHINFO_FILENAME))] = $entry;
    }
}

$linked = 0;
$missing = [];

foreach ($brands as $brand) {
    $slug = strtolower($brand['brand_slug']);
    $lookup = LOGO_FILE_OVERRIDE[$slug] ?? $slug;
    $file = $files[$lookup] ?? null;

    if ($file === null) {
        $missing[] = $brand;
        continue;
    }

    $path = BRAND_IMAGE_DIR . '/' . $file;

    if (!$checkOnly && !$dryRun) {
        $statement = $database->prepare(
            'INSERT INTO brand_images (brand_id, brand_image_path, brand_image_width, brand_image_height)
             VALUES (:brand_id, :path, :width, :height)
             ON DUPLICATE KEY UPDATE
                brand_image_path = VALUES(brand_image_path),
                brand_image_width = VALUES(brand_image_width),
                brand_image_height = VALUES(brand_image_height)',
        );
        $statement->execute([
            ':brand_id' => (int) $brand['brand_id'],
            ':path' => $path,
            ':width' => LOGO_WIDTH,
            ':height' => LOGO_HEIGHT,
        ]);
    }

    ++$linked;
    printf("%-14s %-16s %s\n", $brand['brand_slug'], $brand['brand_name'], $path);
}

printf("\nCó ảnh: %d / %d brand.\n", $linked, count($brands));

if ($missing !== []) {
    fwrite(STDOUT, "\nBrand CHƯA có ảnh (không ghi dòng, giao diện hiện tên thay thế):\n");
    foreach ($missing as $brand) {
        printf("  %-14s %s\n", $brand['brand_slug'], $brand['brand_name']);
    }
}

if ($checkOnly) {
    fwrite(STDOUT, "\n-- đối chiếu bảng brand_images với file trên đĩa --\n");
    $rows = $database->query(
        'SELECT b.brand_slug, b.brand_name, i.brand_image_path
         FROM brand_images i INNER JOIN brands b ON b.brand_id = i.brand_id
         ORDER BY b.brand_slug',
    )->fetchAll();

    $bad = 0;
    foreach ($rows as $row) {
        $absolute = dirname(__DIR__, 2) . '/' . $row['brand_image_path'];
        $ok = is_file($absolute);
        $bad += $ok ? 0 : 1;
        printf("  %-14s %-34s %s\n", $row['brand_slug'], $row['brand_image_path'], $ok ? 'OK' : 'FILE KHÔNG TỒN TẠI');
    }
    printf("\n%d dòng trong DB, %d dòng trỏ tới file không tồn tại.\n", count($rows), $bad);
    exit($bad > 0 ? 1 : 0);
}

fwrite(STDOUT, $dryRun ? "\n(dry-run: chưa ghi DB)\n" : "\nĐã ghi brand_images.\n");
