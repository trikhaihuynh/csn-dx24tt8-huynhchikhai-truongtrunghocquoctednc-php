<?php

declare(strict_types=1);

return [
    'app' => [
        'env' => getenv('APP_ENV') ?: 'development',
        'url' => getenv('APP_URL') ?: 'http://localhost:8080',
        'name' => 'Trường Trung học Quốc tế DNC',
        'per_page' => 10,
    ],
    'db' => [
        'host' => getenv('DB_HOST') ?: 'localhost',
        'port' => getenv('DB_PORT') ?: '3306',
        'name' => getenv('DB_NAME') ?: 'dnc_school',
        'user' => getenv('DB_USER') ?: 'root',
        'pass' => getenv('DB_PASS') ?: '',
    ],
    'upload' => [
        'dir' => PUBLIC_PATH . '/uploads',
        'max_size' => 5 * 1024 * 1024,
        'images' => ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'],
        'docs' => ['application/pdf' => 'pdf', 'image/jpeg' => 'jpg', 'image/png' => 'png'],
    ],
];
