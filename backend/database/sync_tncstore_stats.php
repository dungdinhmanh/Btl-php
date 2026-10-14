<?php
declare(strict_types=1);

/**
 * Nạp giá niệm yết từ tncstore.vn vào products.
 *
 * Usage: php backend/database/sync_tncstore_stats.php [--limit=20] [--category=cpu]
 *        [--delay=250] [--dry-run] [--refresh]
 */

require_once __DIR__ . '/../bootstrap.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

function parseOptions(array $argv): array
{
    $options = [
        'limit' => '0',
        'category' => '',
        'delay' => '250',
        'dry-run' => '0',
        'refresh' => '0',
    ];

    foreach (array_slice($argv, 1) as $argument) {
        if (!str_starts_with($argument, '--')) {
            continue;
        }
        $argument = substr($argument, 2);
        [$key, $value] = str_contains($argument, '=')
            ? explode('=', $argument, 2)
            : [$argument, '1'];

        if (array_key_exists($key, $options)) {
            $options[$key] = $value;
        }
    }

    return $options;
}

function fetchPage(string $url): ?string
{
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'timeout' => 20,
            'follow_location' => 1,
            'max_redirects' => 3,
            'ignore_errors' => true,
            'header' => "User-Agent: Mozilla/5.0 (compatible; TNC-Store-Catalog-Sync/1.0)\r\n"
                . "Accept: text/html,application/xhtml+xml\r\n",
        ],
    ]);

    $body = @file_get_contents($url, false, $context);
    if ($body === false) {
        return null;
    }

    $status = 0;
    foreach ($http_response_header ?? [] as $header) {
        if (preg_match('#^HTTP/\S+\s+(\d{3})#', $header, $matches)) {
            $status = (int) $matches[1];
        }
    }

    return $status === 200 ? $body : null;
}

function extractStats(string $html): ?array
{
    $rating = 0.0;
    $reviews = 0;

    if (preg_match('/"aggregateRating".*?"ratingValue"\s*:\s*"([\d.]+)".*?"reviewCount"\s*:\s*"(\d+)"/s', $html, $matches) === 1) {
        $rating = (float) $matches[1];
        $reviews = (int) $matches[2];
    }

    $listPrice = null;
    if (preg_match('/info-main-price.*?class="old-price"[^>]*>\s*([\d.,]+)\s*đ/s', $html, $matches) === 1) {
        $digits = preg_replace('/[^\d]/', '', $matches[1]);
        if ($digits !== '' && $digits !== null && (int) $digits > 0) {
            $listPrice = (float) $digits;
        }
    }

    $percent = 0;
    if (preg_match('/class="saleoff"[^>]*>\s*-?(\d+)\s*%/s', $html, $matches) === 1) {
        $percent = (int) $matches[1];
    }

    if ($reviews === 0 && $listPrice === null) {
        return null;
    }

    return [
        'rating' => min(5.0, max(0.0, round($rating, 1))),
        'reviews' => $reviews,
        'listPrice' => $listPrice,
        'percent' => $percent,
    ];
}

$options = parseOptions($argv);
$database = database();
$dryRun = $options['dry-run'] !== '0';
$delayMs = max(0, (int) $options['delay']);
$limit = max(0, (int) $options['limit']);

$sql = 'SELECT product_id AS id, product_slug AS slug, product_name AS name,
               unit_price AS price, rating_avg AS rating, review_count AS reviews
        FROM products
        WHERE product_status = :status';

$params = [':status' => 'active'];

if ($options['category'] !== '') {
    $sql .= ' AND category_id = (SELECT category_id FROM categories WHERE category_slug = :slug)';
    $params[':slug'] = $options['category'];
}

if ($options['refresh'] === '0') {
    $sql .= ' AND review_count = 0';
}

$sql .= ' ORDER BY product_id ASC';
if ($limit > 0) {
    $sql .= ' LIMIT ' . $limit;
}

$statement = $database->prepare($sql);
$statement->execute($params);
$products = $statement->fetchAll();

if ($products === []) {
    fwrite(STDOUT, "Không có sản phẩm nào cần đồng bộ.\n");
    exit(0);
}

$update = $database->prepare(
    'UPDATE products
     SET list_price = :list_price, rating_avg = :rating, review_count = :reviews
     WHERE product_id = :id',
);

$updated = 0;
$skipped = 0;
$missing = [];

foreach ($products as $index => $product) {
    $slug = (string) $product['slug'];
    $url = 'https://www.tncstore.vn/' . rawurlencode($slug) . '.html';
    $html = fetchPage($url);

    if ($html === null) {
        $missing[] = $slug;
        fwrite(STDOUT, sprintf("[%d/%d] %-52s KHÔNG TẢI ĐƯỢC\n", $index + 1, count($products), $slug));
    } else {
        $stats = extractStats($html);
        $price = (float) $product['price'];

        if ($stats === null) {
            ++$skipped;
            fwrite(STDOUT, sprintf("[%d/%d] %-52s không có dữ liệu\n", $index + 1, count($products), $slug));
        } else {
            $listPrice = $stats['listPrice'] !== null && $stats['listPrice'] > $price
                ? $stats['listPrice']
                : null;

            if (!$dryRun) {
                $update->execute([
                    ':list_price' => $listPrice,
                    ':rating' => $stats['rating'],
                    ':reviews' => $stats['reviews'],
                    ':id' => (int) $product['id'],
                ]);
            }

            ++$updated;
            fwrite(STDOUT, sprintf(
                "[%d/%d] %-52s %s★ / %d đánh giá%s%s\n",
                $index + 1,
                count($products),
                $slug,
                $stats['rating'],
                $stats['reviews'],
                $listPrice !== null ? ' / giá gạch ' . number_format($listPrice, 0, ',', '.') . 'đ' : '',
                $dryRun ? ' (dry-run)' : '',
            ));
        }
    }

    if ($delayMs > 0 && $index + 1 < count($products)) {
        usleep($delayMs * 1000);
    }
}

fwrite(STDOUT, sprintf(
    "\nXong: %d cập nhật, %d bỏ qua, %d không tải được%s.\n",
    $updated,
    $skipped,
    count($missing),
    $dryRun ? ' (chưa ghi DB)' : '',
));

if ($missing !== []) {
    fwrite(STDOUT, "Slug không có trang tương ứng (giữ nguyên dữ liệu cũ):\n  " . implode("\n  ", $missing) . "\n");
}
