<?php
declare(strict_types=1);
require_once __DIR__ . '/../bootstrap.php';

try {
    $repository = new ProductRepository(database());
    $catalog = require __DIR__ . '/../config/category_catalog.php';

    $categories = array_map(static function (array $category) use ($catalog): array {
        $metadata = $catalog[$category['slug']] ?? [];

        $category['banner'] = $metadata['banner'] ?? null;
        $category['bannerAlt'] = $metadata['bannerAlt'] ?? $category['name'];
        $category['priceFilter'] = $metadata['price'] ?? null;

        return $category;
    }, $repository->categories());

    // Thứ tự hiển thị lấy theo đúng thứ tự khai báo trong config, danh mục
    // chưa có metadata thì xếp sau và theo tên.
    $position = array_flip(array_keys($catalog));
    usort($categories, static function (array $a, array $b) use ($position): int {
        $order = ($position[$a['slug']] ?? PHP_INT_MAX) <=> ($position[$b['slug']] ?? PHP_INT_MAX);

        return $order !== 0 ? $order : strcmp($a['name'], $b['name']);
    });

    // Bộ lọc hãng chỉ cần các hãng có sản phẩm trong danh mục đang xem.
    // Không truyền category thì giữ danh sách hãng toàn cửa hàng.
    $brandCategories = array_values(array_filter(array_map(
        'trim',
        explode(',', (string) ($_GET['category'] ?? '')),
    )));

    jsonResponse([
        'ok' => true,
        'data' => [
            'categories' => $categories,
            'brands' => $repository->brands($brandCategories),
        ],
    ]);
} catch (Throwable $exception) {
    apiError($exception);
}
