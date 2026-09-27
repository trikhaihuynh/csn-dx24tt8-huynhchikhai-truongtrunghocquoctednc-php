<?php

declare(strict_types=1);

namespace App\Core;

final class Csrf
{
    public static function token(): string
    {
        return $_SESSION['_token'] ??= bin2hex(random_bytes(32));
    }

    public static function field(): string
    {
        return '<input type="hidden" name="_token" value="' . e(self::token()) . '">';
    }

    public static function check(): void
    {
        $submittedToken = $_POST['_token'] ?? '';
        if (!is_string($submittedToken) || !hash_equals(self::token(), $submittedToken)) {
            http_response_code(403);
            View::render('errors/403', [
                'title' => 'Yêu cầu không hợp lệ',
                'message' => 'Phiên làm việc đã hết hạn hoặc yêu cầu không hợp lệ. Vui lòng tải lại trang và thử lại.',
            ], 'public');
            exit;
        }
    }
}
