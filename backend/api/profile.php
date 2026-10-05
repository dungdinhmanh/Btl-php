<?php
declare(strict_types=1);
require_once __DIR__ . '/../bootstrap.php';

try {
    $userId = (int) ($_SESSION['user']['id'] ?? 0);
    if ($userId === 0) {
        jsonResponse(['ok' => false, 'message' => 'Not signed in.'], 401);
    }

    $profile = new ProfileRepository(database());
    $user = $profile->user($userId);
    if ($user === null) {
        // Tài khoản đã bị xóa/khóa sau khi đăng nhập: hủy phiên luôn.
        $_SESSION = [];
        jsonResponse(['ok' => false, 'message' => 'Account is not available.'], 401);
    }

    jsonResponse([
        'ok' => true,
        'data' => [
            'user' => $user,
            'stats' => $profile->stats($userId),
            'orders' => $profile->orders($userId),
            'address' => $profile->defaultAddress($userId),
        ],
    ]);
} catch (Throwable $exception) {
    apiError($exception);
}
