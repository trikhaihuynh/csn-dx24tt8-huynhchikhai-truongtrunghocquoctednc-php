<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected string $layout = 'public';

    protected function view(string $view, array $data = []): void
    {
        View::render($view, $data, $this->layout);
    }

    protected function redirect(string $path): never
    {
        header('Location: ' . url($path));
        exit;
    }

    protected function back(): never
    {
        $this->redirect($this->sameSitePathFromReferer());
    }

    private function sameSitePathFromReferer(): string
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        $path = parse_url($referer, PHP_URL_PATH) ?: '/';
        $queryString = parse_url($referer, PHP_URL_QUERY);

        return $path . ($queryString ? '?' . $queryString : '');
    }

    protected function flash(string $type, string $message): void
    {
        $_SESSION['_flash'] = ['type' => $type, 'message' => $message];
    }

    protected function post(string $key, mixed $default = ''): mixed
    {
        if (!isset($_POST[$key])) {
            return $default;
        }

        return is_string($_POST[$key]) ? trim($_POST[$key]) : $_POST[$key];
    }

    protected function query(string $key, mixed $default = ''): mixed
    {
        return $_GET[$key] ?? $default;
    }

    protected function isPost(): bool
    {
        return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
    }

    protected function withErrors(array $errors, array $oldInput, string $redirectTo): never
    {
        $_SESSION['_errors'] = $errors;
        $_SESSION['_old'] = $this->withoutSecretFields($oldInput);
        $this->redirect($redirectTo);
    }

    private function withoutSecretFields(array $input): array
    {
        unset($input['_token']);
        foreach ($input as $field => $value) {
            if (str_contains((string) $field, 'mat_khau') || !is_scalar($value)) {
                unset($input[$field]);
            }
        }

        return $input;
    }

    protected function notFound(): never
    {
        http_response_code(404);
        View::render('errors/404', ['title' => 'Không tìm thấy trang'], $this->layout);
        exit;
    }
}
