<?php
declare(strict_types=1);
require_once __DIR__ . '/../bootstrap.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new InvalidArgumentException('Only POST requests are accepted.');
    }
    $input = requestInput();
    $name = trim((string) ($input['name'] ?? ''));
    $email = filter_var($input['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $password = (string) ($input['password'] ?? '');
    if ($name === '' || !$email || strlen($password) < 8) {
        throw new InvalidArgumentException('Name, a valid email, and a password of at least 8 characters are required.');
    }

    $users = new UserRepository(database());
    if ($users->findByEmail($email)) {
        jsonResponse(['ok' => false, 'message' => 'This email is already registered.'], 409);
    }
    $id = $users->create($name, $email, $password);
    $_SESSION['user'] = ['id' => $id, 'name' => $name, 'role' => 'customer'];
    jsonResponse(['ok' => true, 'data' => $_SESSION['user']], 201);
} catch (Throwable $exception) {
    apiError($exception);
}
