<?php
declare(strict_types=1);
require_once __DIR__ . '/../bootstrap.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new InvalidArgumentException('Only POST requests are accepted.');
    }
    $input = requestInput();
    $email = filter_var($input['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $password = (string) ($input['password'] ?? '');
    if (!$email || $password === '') {
        throw new InvalidArgumentException('Email and password are required.');
    }

    $user = (new UserRepository(database()))->findByEmail($email);
    if (!$user || !password_verify($password, $user['password_hash'])) {
        jsonResponse(['ok' => false, 'message' => 'Invalid email or password.'], 401);
    }

    session_regenerate_id(true);
    $_SESSION['user'] = ['id' => (int) $user['id'], 'name' => $user['name'], 'role' => $user['role']];
    jsonResponse(['ok' => true, 'data' => $_SESSION['user']]);
} catch (Throwable $exception) {
    apiError($exception);
}
