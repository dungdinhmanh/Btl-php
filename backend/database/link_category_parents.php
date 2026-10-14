<?php
declare(strict_types=1);

/**
 * Gắn danh mục lá vào danh mục CHA để breadcrumb có 3 cấp như tncstore.vn.
 *
 * Ánh xạ lấy từ breadcrumb THẬT trên tncstore.vn — đã đọc `#breadcrumb` của nhiều trang
 * sản phẩm (mỗi danh mục lấy 2 sản phẩm khác nhau), không suy đoán:
 *
 *   /cpu-intel-core-i9-14900kf-tray.html      → Trang chủ › Linh kiện máy tính › CPU - Bộ vi xử lý
 *   /bo-mach-chu-gigabyte-x870-...html        → Trang chủ › Linh kiện máy tính › Mainboard - Bo mạch chủ
 *   /ram-teamgroup-vulcan-16gb-...html        → Trang chủ › Linh kiện máy tính › RAM - Bộ nhớ trong
 *   /o-cung-hdd-western-digital-blue-4tb...   → Trang chủ › Linh kiện máy tính › Ổ cứng HDD
 *   /vo-case-lian-li-o11-...html              → Trang chủ › Linh kiện máy tính › Case - Vỏ máy tính
 *   /nguon-may-tinh-asus-rog-thor-...html     → Trang chủ › Linh kiện máy tính › PSU - Nguồn máy tính
 *   /o-cung-ssd-kioxia-exceria-plus-g4...     → Trang chủ › Linh kiện máy tính › Ổ cứng SSD
 *   /man-hinh-gaming-lg-24g411b-b.html        → Trang chủ › Màn Hình Máy Tính › Màn Hình LG
 *   /ban-phim-gaming-asus-m705-...html        → Trang chủ › Gaming Gears › Bàn Phím Cơ
 *
 * `vga` KHÔNG có danh mục cha: bằng chứng
 *   /card-man-hinh-asus-dual-radeon-rx-9050-8gb-oc-edition.html
 *     → Trang chủ › VGA - Card màn hình   (chỉ 2 cấp)
 * nên "VGA - Card màn hình" nằm ngay dưới Trang chủ, giống hệt "Ổ cứng HDD" về cấp.
 *
 * Script chạy lại được nhiều lần (idempotent): chỉ thêm danh mục cha nếu thiếu,
 * chỉ gán parent_category_id nếu khác giá trị hiện tại.
 *
 * Usage: php backend/database/link_category_parents.php [--dry-run] [--unlink]
 */

require_once __DIR__ . '/../bootstrap.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

/**
 * Danh mục CHA => các slug danh mục lá thuộc về nó.
 *
 * `display` CỐ TÌNH không có cha. Lý do: tncstore.vn để các danh mục màn hình theo
 * BRAND ("Màn Hình LG", "Màn Hình VSP") — cấp lá của trang gốc không phải "màn hình".
 * Ta gộp hết vào một danh mục lá tên "Màn hình máy tính", gắn cha "Màn Hình Máy Tính"
 * vào sẽ ra breadcrumb "Màn Hình Máy Tính › Màn hình máy tính" — hai tên trùng nghĩa.
 * Bỏ cha thì ra 2 cấp, đúng cấu trúc như `vga` (xem bằng chứng ở trên).
 * Chủ dự án chốt ngày 2026-10-15. Xem PLAN.md mục 5.10.
 */
const PARENTS = [
    'linh-kien-may-tinh' => ['cpu', 'mainboard', 'ram', 'hdd', 'case', 'psu', 'ssd'],
    'gaming-gears' => ['phu-kien'],
];

/** Danh mục cha không còn danh mục lá nào — xoá khỏi DB để tránh rác. */
const ORPHAN_PARENTS = ['man-hinh-may-tinh'];

$dryRun = in_array('--dry-run', array_slice($argv, 1), true);
$unlink = in_array('--unlink', array_slice($argv, 1), true);

$database = database();

$existing = [];
foreach ($database->query('SELECT category_id, category_slug, category_name, parent_category_id FROM categories')->fetchAll() as $row) {
    $existing[$row['category_slug']] = $row;
}

if ($unlink) {
    $database->exec('UPDATE categories SET parent_category_id = NULL WHERE parent_category_id IS NOT NULL');
    $statement = $database->prepare('DELETE FROM categories WHERE category_slug = ?');
    foreach (array_merge(array_keys(PARENTS), ORPHAN_PARENTS) as $slug) {
        $statement->execute([$slug]);
    }
    fwrite(STDOUT, "Đã gỡ toàn bộ danh mục cha.\n");
    exit(0);
}

// Danh mục cha đã bị bỏ khỏi PARENTS (vì danh mục lá của nó được chốt là không có cha)
// thì gỡ liên kết rồi xoá hẳn khỏi DB — không để lại dòng rác không ai dùng tới.
$delete = $database->prepare('DELETE FROM categories WHERE category_slug = ?');
foreach (ORPHAN_PARENTS as $orphanSlug) {
    if (!isset($existing[$orphanSlug])) {
        continue;
    }

    if ($dryRun) {
        printf("Sẽ gỡ cha của các danh mục lá rồi xoá \"%s\"\n", $orphanSlug);
        continue;
    }

    $database->exec('UPDATE categories SET parent_category_id = NULL WHERE parent_category_id = ' . (int) $existing[$orphanSlug]['category_id']);
    $delete->execute([$orphanSlug]);
    printf("Đã gỡ cha của các danh mục lá và xoá danh mục cha \"%s\"\n", $orphanSlug);
}

foreach (PARENTS as $parentSlug => $childSlugs) {
    if (isset($existing[$parentSlug])) {
        $parentId = (int) $existing[$parentSlug]['category_id'];
        printf("Danh mục cha đã có: %-22s #%d\n", $parentSlug, $parentId);
    } else {
        $parentName = match ($parentSlug) {
            'linh-kien-may-tinh' => 'Linh kiện máy tính',
            'gaming-gears' => 'Gaming Gears',
        };

        if ($dryRun) {
            printf("Sẽ thêm danh mục cha: %-22s \"%s\"\n", $parentSlug, $parentName);
            $parentId = 0;
        } else {
            $statement = $database->prepare(
                'INSERT INTO categories (category_name, category_slug) VALUES (:name, :slug)',
            );
            $statement->execute([':name' => $parentName, ':slug' => $parentSlug]);
            $parentId = (int) $database->lastInsertId();
            printf("Đã thêm danh mục cha: %-22s #%d \"%s\"\n", $parentSlug, $parentId, $parentName);
        }
    }

    foreach ($childSlugs as $childSlug) {
        if (!isset($existing[$childSlug])) {
            printf("  THIẾU danh mục lá: %s — không gán được\n", $childSlug);
            continue;
        }

        $child = $existing[$childSlug];
        if ($parentId > 0 && (int) $child['parent_category_id'] === $parentId) {
            printf("  %-14s đã gán đúng cha\n", $childSlug);
            continue;
        }

        if (!$dryRun && $parentId > 0) {
            $statement = $database->prepare(
                'UPDATE categories SET parent_category_id = :parent WHERE category_id = :id',
            );
            $statement->execute([':parent' => $parentId, ':id' => (int) $child['category_id']]);
        }

        printf(
            "  %-14s cha %s -> %d%s\n",
            $childSlug,
            $child['parent_category_id'] === null ? 'NULL' : (string) (int) $child['parent_category_id'],
            $parentId,
            $dryRun ? ' (dry-run)' : '',
        );
    }
}

fwrite(STDOUT, $dryRun ? "\n(dry-run: chưa ghi DB)\n" : "\nXong.\n");
