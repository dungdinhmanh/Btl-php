<?php
declare(strict_types=1);

if (!isset($legacyPage) || !is_string($legacyPage) || !preg_match('/^[a-z0-9-]+$/', $legacyPage)) {
    http_response_code(500);
    exit('Invalid PHP page entry point.');
}

$legacyPath = PROJECT_PATH . DIRECTORY_SEPARATOR . $legacyPage . '.html';
if (!is_file($legacyPath)) {
    http_response_code(404);
    exit('Page not found.');
}

$html = (string) file_get_contents($legacyPath);
$phpPages = [
    'index', 'about', 'admin', 'buildpc', 'cart', 'checkout', 'contact', 'forgot-password',
    'login', 'news', 'news-post', 'product-detail', 'products', 'profile', 'register', 'success', '404',
];
foreach ($phpPages as $page) {
    $html = str_replace("{$page}.html", "{$page}.php", $html);
}

$clientConfig = '<script>window.TNC_API_BASE = "backend/api";</script>';
$html = str_replace('</head>', $clientConfig . '</head>', $html);
echo $html;
