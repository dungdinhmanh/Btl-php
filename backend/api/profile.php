<?php
declare(strict_types=1);
require_once __DIR__ . '/../bootstrap.php';

try {
    $userId = (int) ($_SESSION['user']['id'] ?? 0);
    if ($userId === 0) {
        jsonResponse(['ok' => false, 'message' => 'Not signed in.'], 401);
    }

    $profile = new ProfileRepository(database());
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = requestInput();
        $action = (string) ($input['action'] ?? '');
        $currentPassword = (string) ($input['currentPassword'] ?? '');
        if ($currentPassword === '') {
            throw new InvalidArgumentException('Vui lòng nhập mật khẩu hiện tại.');
        }

        if ($action === 'update-details') {
            $name = trim((string) ($input['name'] ?? ''));
            $email = filter_var(trim((string) ($input['email'] ?? '')), FILTER_VALIDATE_EMAIL);
            $phone = trim((string) ($input['phone'] ?? ''));
            $nameLength = preg_match_all('/./us', $name);
            $phoneLength = preg_match_all('/./us', $phone);
            if ($name === '' || $nameLength === false || $nameLength > 150 || !$email || $phoneLength === false || $phoneLength > 25) {
                throw new InvalidArgumentException('Vui lòng kiểm tra họ tên, email và số điện thoại.');
            }
            try {
                $updated = $profile->updateDetails($userId, $name, $email, $phone, $currentPassword);
            } catch (PDOException $exception) {
                if ($exception->getCode() === '23000') {
                    jsonResponse(['ok' => false, 'message' => 'Email này đã được sử dụng.'], 409);
                }
                throw $exception;
            }
            if (!$updated) {
                jsonResponse(['ok' => false, 'message' => 'Mật khẩu hiện tại không chính xác.'], 403);
            }
            $_SESSION['user']['name'] = $name;
            jsonResponse(['ok' => true, 'message' => 'Đã cập nhật thông tin tài khoản.']);
        }

        if ($action === 'update-password') {
            $newPassword = (string) ($input['newPassword'] ?? '');
            $confirmPassword = (string) ($input['confirmPassword'] ?? '');
            if (strlen($newPassword) < 8 || $newPassword !== $confirmPassword) {
                throw new InvalidArgumentException('Mật khẩu mới phải có ít nhất 8 ký tự và khớp với xác nhận.');
            }
            if (!$profile->updatePassword($userId, $currentPassword, $newPassword)) {
                jsonResponse(['ok' => false, 'message' => 'Mật khẩu hiện tại không chính xác.'], 403);
            }
            session_regenerate_id(true);
            jsonResponse(['ok' => true, 'message' => 'Đã đổi mật khẩu.']);
        }

        throw new InvalidArgumentException('Yêu cầu cập nhật không hợp lệ.');
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        jsonResponse(['ok' => false, 'message' => 'Phương thức không được hỗ trợ.'], 405);
    }

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
