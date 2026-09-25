<?php

declare(strict_types=1);

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function url(string $path = ''): string
{
    return rtrim(APP_URL, '/') . '/' . ltrim($path, '/');
}

function safe_url(?string $url): string
{
    $url = trim((string) $url);

    return preg_match('#^https?://#i', $url) === 1 ? $url : '#';
}

function asset(string $path): string
{
    return url('assets/' . ltrim($path, '/'));
}

function upload_url(?string $relativePath): string
{
    $relativePath = ltrim((string) $relativePath, '/');
    if ($relativePath === '' || !is_file(PUBLIC_PATH . '/uploads/' . $relativePath)) {
        return asset('img/placeholder.jpg');
    }

    return url('uploads/' . $relativePath);
}

function old(string $key, mixed $default = ''): mixed
{
    return $_SESSION['_old'][$key] ?? $default;
}

function flash_get(): ?array
{
    $flash = $_SESSION['_flash'] ?? null;
    unset($_SESSION['_flash']);

    return $flash;
}

function format_date(?string $datetime, string $format = 'd/m/Y'): string
{
    if ($datetime === null || $datetime === '') {
        return '';
    }
    $timestamp = strtotime($datetime);

    return $timestamp === false ? '' : date($format, $timestamp);
}

function slugify(string $text): string
{
    if (class_exists(Normalizer::class)) {
        $text = Normalizer::normalize($text, Normalizer::FORM_C) ?: $text;
    }
    $text = mb_strtolower($text, 'UTF-8');

    $vietnameseMap = [
        'a' => ['à', 'á', 'ạ', 'ả', 'ã', 'â', 'ầ', 'ấ', 'ậ', 'ẩ', 'ẫ', 'ă', 'ằ', 'ắ', 'ặ', 'ẳ', 'ẵ'],
        'e' => ['è', 'é', 'ẹ', 'ẻ', 'ẽ', 'ê', 'ề', 'ế', 'ệ', 'ể', 'ễ'],
        'i' => ['ì', 'í', 'ị', 'ỉ', 'ĩ'],
        'o' => ['ò', 'ó', 'ọ', 'ỏ', 'õ', 'ô', 'ồ', 'ố', 'ộ', 'ổ', 'ỗ', 'ơ', 'ờ', 'ớ', 'ợ', 'ở', 'ỡ'],
        'u' => ['ù', 'ú', 'ụ', 'ủ', 'ũ', 'ư', 'ừ', 'ứ', 'ự', 'ử', 'ữ'],
        'y' => ['ỳ', 'ý', 'ỵ', 'ỷ', 'ỹ'],
        'd' => ['đ'],
    ];
    foreach ($vietnameseMap as $plainLetter => $accentedLetters) {
        $text = str_replace($accentedLetters, $plainLetter, $text);
    }

    $textWithoutCombiningMarks = preg_replace('/\p{Mn}+/u', '', $text) ?? $text;
    $slug = preg_replace('/[^a-z0-9]+/', '-', $textWithoutCombiningMarks) ?? $textWithoutCombiningMarks;

    return trim($slug, '-');
}

function setting(string $key, string $default = ''): string
{
    static $settings = null;
    if ($settings === null) {
        try {
            $settings = (new App\Models\Setting())->all();
        } catch (Throwable $exception) {
            error_log('Không đọc được cai_dat: ' . $exception->getMessage());
            $settings = [];
        }
    }

    return $settings[$key] ?? $default;
}

function is_active(string $prefix, bool $exact = false): string
{
    $currentPath = App\Core\Router::normalizePath($_SERVER['REQUEST_URI'] ?? '/');
    if ($prefix === '/' || $exact) {
        return $currentPath === $prefix ? 'active' : '';
    }

    return ($currentPath === $prefix || str_starts_with($currentPath, $prefix . '/')) ? 'active' : '';
}
