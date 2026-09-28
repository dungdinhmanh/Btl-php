<?php
declare(strict_types=1);

function database(): PDO
{
    static $connection = null;

    if ($connection instanceof PDO) {
        return $connection;
    }

    $host = getenv('DB_HOST') ?: '127.0.0.1';
    $port = getenv('DB_PORT') ?: '3306';
    $name = getenv('DB_DATABASE') ?: '';
    $user = getenv('DB_USERNAME') ?: '';
    $password = getenv('DB_PASSWORD') ?: '';
    $sslCa = getenv('DB_SSL_CA') ?: '';
    $sslVerify = filter_var(getenv('DB_SSL_VERIFY') ?: 'true', FILTER_VALIDATE_BOOL);

    if ($name === '' || $user === '') {
        throw new RuntimeException('Database is not configured. Copy backend/config/.env.example to .env and fill in the connection values.');
    }

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_STRINGIFY_FETCHES => false,
        PDO::ATTR_TIMEOUT => 10,
    ];

    if ($sslCa !== '') {
        // Relative CA paths are resolved against this config directory.
        $caPath = str_starts_with($sslCa, DIRECTORY_SEPARATOR) || preg_match('/^[A-Za-z]:[\\\\\/]/', $sslCa)
            ? $sslCa
            : __DIR__ . DIRECTORY_SEPARATOR . $sslCa;

        if (!is_file($caPath)) {
            throw new RuntimeException("Database TLS CA file not found at {$caPath}. Check DB_SSL_CA in backend/config/.env.");
        }

        $options[PDO::MYSQL_ATTR_SSL_CA] = $caPath;
        $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = $sslVerify;
    }

    $connection = new PDO(
        "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4",
        $user,
        $password,
        $options,
    );

    return $connection;
}
