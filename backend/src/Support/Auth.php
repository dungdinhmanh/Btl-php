<?php
declare(strict_types=1);

/**
 * Phân quyền dùng chung. Vai trò (roles.role_code): customer, staff, admin.
 *
 * $_SESSION['user'] chỉ là bản ghi nhớ lúc đăng nhập, nên mỗi lần kiểm tra quyền đều hỏi lại DB:
 * tài khoản bị khóa hoặc bị hạ quyền sẽ mất quyền ngay, không phải chờ hết phiên đăng nhập.
 */
const STAFF_ROLES = ['staff', 'admin'];
const ADMIN_ROLES = ['admin'];

/** Người dùng đang đăng nhập (đã xác minh lại với DB), hoặc null nếu chưa đăng nhập/tài khoản không còn hiệu lực. */
function currentUser(): ?array
{
    static $resolved = false;
    static $user = null;
    if ($resolved) {
        return $user;
    }
    $resolved = true;

    $id = (int) ($_SESSION['user']['id'] ?? 0);
    if ($id === 0) {
        return null;
    }

    $statement = database()->prepare(
        'SELECT u.user_id AS id, u.full_name AS name, r.role_code AS role
         FROM users u INNER JOIN roles r ON r.role_id = u.role_id
         WHERE u.user_id = :id AND u.account_status = :status',
    );
    $statement->execute([':id' => $id, ':status' => 'active']);
    $row = $statement->fetch();
    if ($row === false) {
        unset($_SESSION['user']); // tài khoản đã bị xóa hoặc khóa: bỏ luôn phiên cũ
        return null;
    }

    $user = ['id' => (int) $row['id'], 'name' => $row['name'], 'role' => $row['role']];
    $_SESSION['user'] = $user; // đồng bộ lại tên/vai trò nếu đã đổi
    return $user;
}

/** Cho API: chưa đăng nhập -> 401, sai vai trò -> 403 (đều trả JSON và dừng ngay). */
function requireRole(array $roles): array
{
    $user = currentUser();
    if ($user === null) {
        jsonResponse(['ok' => false, 'message' => 'Bạn cần đăng nhập để tiếp tục.'], 401);
    }
    if (!in_array($user['role'], $roles, true)) {
        jsonResponse(['ok' => false, 'message' => 'Bạn không có quyền thực hiện thao tác này.'], 403);
    }

    return $user;
}

/** Cho trang: chưa đăng nhập -> chuyển tới trang đăng nhập, sai vai trò -> trang 403. */
function requirePageRole(array $roles, string $loginUrl = 'login.php'): array
{
    try {
        $user = currentUser();
    } catch (Throwable) {
        http_response_code(503);
        exit('Chưa kết nối được cơ sở dữ liệu.');
    }

    if ($user === null) {
        header('Location: ' . $loginUrl);
        exit;
    }
    if (!in_array($user['role'], $roles, true)) {
        http_response_code(403);
        header('Content-Type: text/html; charset=utf-8');
        echo '<!doctype html><html lang="vi"><head><meta charset="utf-8"><title>Không có quyền truy cập</title></head>'
            . '<body style="font-family:sans-serif;text-align:center;padding:4rem 1rem">'
            . '<h1>403</h1><p>Tài khoản của bạn không có quyền truy cập trang này.</p>'
            . '<p><a href="' . htmlspecialchars($loginUrl === 'login.php' ? 'index.php' : '../index.php', ENT_QUOTES) . '">Về trang chủ</a></p>'
            . '</body></html>';
        exit;
    }

    return $user;
}

/**
 * Mặc định từ chối cho khu vực quản trị: mọi trang trong admin/, admin.php và mọi API trong
 * backend/api/admin/ đều bắt buộc nhân viên/quản trị, kể cả file mới thêm sau này mà quên gọi
 * requirePageRole()/requireRole(). Được gọi một lần ở cuối bootstrap.php.
 * (Các file vẫn nên tự gọi guard để lấy thông tin người dùng hoặc siết quyền chặt hơn, ví dụ DELETE sản phẩm.)
 */
function guardAdminArea(): void
{
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');

    if (preg_match('#/backend/api/admin/[^/]+$#', $script)) {
        try {
            requireRole(STAFF_ROLES);
        } catch (Throwable $exception) {
            apiError($exception); // mất kết nối DB -> 503 dạng JSON thay vì lỗi trắng trang
        }
    } elseif (preg_match('#(^|/)admin(\.php|/[^/]+\.php)$#', $script)) {
        requirePageRole(STAFF_ROLES, str_contains($script, '/admin/') ? '../login.php' : 'login.php');
    }
}
