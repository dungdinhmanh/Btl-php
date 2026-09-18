<?php
declare(strict_types=1);

function requestInput(): array
{
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (str_contains($contentType, 'application/json')) {
        $data = json_decode((string) file_get_contents('php://input'), true);
        return is_array($data) ? $data : [];
    }

    return $_POST;
}

function jsonResponse(array $payload, int $status = 200): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function apiError(Throwable $exception): never
{
    $status = $exception instanceof InvalidArgumentException ? 422 : 503;
    jsonResponse([
        'ok' => false,
        'message' => $status === 503 ? 'The database is not connected yet.' : $exception->getMessage(),
    ], $status);
}
