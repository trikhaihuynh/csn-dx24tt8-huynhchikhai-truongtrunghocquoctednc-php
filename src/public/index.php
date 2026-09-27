<?php

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('PUBLIC_PATH', __DIR__);

$config = require APP_PATH . '/config/config.php';
define('APP_URL', $config['app']['url']);
define('APP_ENV', $config['app']['env']);

error_reporting(E_ALL);
if (APP_ENV === 'development') {
    ini_set('display_errors', '1');
} else {
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
}

spl_autoload_register(static function (string $className): void {
    if (!str_starts_with($className, 'App\\')) {
        return;
    }
    $segments = explode('\\', substr($className, 4));
    $fileName = array_pop($segments);
    $directory = strtolower(implode('/', $segments));
    $filePath = APP_PATH . '/' . $directory . '/' . $fileName . '.php';
    if (is_file($filePath)) {
        require $filePath;
    }
});

require APP_PATH . '/core/helpers.php';

session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');

$router = new App\Core\Router();
require APP_PATH . '/routes.php';

try {
    $router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
} catch (Throwable $exception) {
    if (APP_ENV === 'development') {
        throw $exception;
    }
    error_log((string) $exception);
    http_response_code(500);
    App\Core\View::render('errors/500', ['title' => 'Lỗi hệ thống'], 'public');
}
