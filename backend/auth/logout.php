<?php
declare(strict_types=1);
require_once __DIR__ . '/../bootstrap.php';

// Chỉ nhận POST: GET có thể bị nhúng vào <img src> của trang khác để đăng xuất người dùng.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['ok' => false, 'message' => 'Only POST requests are accepted.'], 405);
}

$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $cookie = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $cookie['path'], $cookie['domain'], $cookie['secure'], $cookie['httponly']);
}
session_destroy();

// Form thường -> về trang chủ; gọi bằng AJAX (Accept: application/json) -> trả JSON.
if (str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json')) {
    jsonResponse(['ok' => true]);
}
header('Location: ../../index.php');
exit;
