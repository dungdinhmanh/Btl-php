<?php
declare(strict_types=1);

const BACKEND_PATH = __DIR__;
const PROJECT_PATH = __DIR__ . DIRECTORY_SEPARATOR . '..';

function loadEnvironment(string $path): void
{
    if (!is_file($path)) {
        return;
    }

    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        if ($key !== '' && getenv($key) === false) {
            putenv("{$key}={$value}");
        }
    }
}

loadEnvironment(BACKEND_PATH . '/config/.env');

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name('tnc_store_session');
    session_start();
}

require_once BACKEND_PATH . '/config/database.php';
require_once BACKEND_PATH . '/src/Support/Http.php';
require_once BACKEND_PATH . '/src/Repositories/ProductRepository.php';
require_once BACKEND_PATH . '/src/Repositories/NewsRepository.php';
require_once BACKEND_PATH . '/src/Repositories/UserRepository.php';
