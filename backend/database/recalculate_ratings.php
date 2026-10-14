<?php
declare(strict_types=1);

/**
 * Tính lại products.rating_avg / review_count từ bảng product_reviews.
 *
 * products lưu sẵn điểm trung bình để thẻ sản phẩm chỉ cần SELECT một bảng.
 * Bình thường ReviewRepository tự cập nhật trong cùng transaction với thao tác ghi,
 * nên script này chỉ cần chạy khi nghi ngờ hai bên lệch nhau.
 *
 * Usage: php backend/database/recalculate_ratings.php [--only=PRODUCT_ID]
 */

require_once __DIR__ . '/../bootstrap.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

$only = 0;
foreach (array_slice($argv, 1) as $argument) {
    if (str_starts_with($argument, '--only=')) {
        $only = (int) substr($argument, 7);
    }
}

$repository = new ReviewRepository(database());

if ($only > 0) {
    $repository->refreshProductRating($only);
    printf("Đã tính lại điểm cho sản phẩm #%d.\n", $only);
    exit(0);
}

$changed = $repository->refreshAllProducts();
printf("Đã tính lại %d sản phẩm.\n", $changed);

$statement = database()->query(
    "SELECT product_id AS id, product_slug AS slug, product_name AS name,
            rating_avg AS rating, review_count AS reviews
     FROM products
     WHERE review_count > 0
     ORDER BY rating_avg DESC, product_id ASC",
);

$rows = $statement->fetchAll();
if ($rows === []) {
    fwrite(STDOUT, "Chưa có đánh giá nào.\n");
    exit(0);
}

fwrite(STDOUT, sprintf("\n%d sản phẩm có đánh giá:\n", count($rows)));
foreach ($rows as $row) {
    printf("  %4.1f★  %3d đánh giá  %s\n", (float) $row['rating'], (int) $row['reviews'], $row['name']);
}
