<?php

declare(strict_types=1);

namespace App\Core;

use finfo;
use RuntimeException;

final class Upload
{
    public static function image(?array $file, string $group): ?string
    {
        return self::store($file, $group, 'images');
    }

    public static function document(?array $file, string $group): ?string
    {
        return self::store($file, $group, 'docs');
    }

    public static function images(array $files, string $group): array
    {
        $result = ['saved' => [], 'errors' => []];
        foreach (self::normalizeFiles($files) as $index => $file) {
            if ($file['error'] === UPLOAD_ERR_NO_FILE) {
                continue;
            }
            try {
                $result['saved'][$index] = self::store($file, $group, 'images');
            } catch (RuntimeException $exception) {
                $fileName = $file['name'] !== '' ? $file['name'] : 'Tệp ' . ($index + 1);
                $result['errors'][self::uniqueKey($result['errors'], $fileName)] = $exception->getMessage();
            }
        }

        return $result;
    }

    public static function normalizeFiles(array $files): array
    {
        if (!isset($files['name'], $files['tmp_name'], $files['error'], $files['size'])) {
            return [];
        }
        if (!is_array($files['name'])) {
            $files = array_map(static fn (mixed $value): array => [$value], $files);
        }

        $normalized = [];
        foreach (array_keys($files['name']) as $position => $key) {
            $name = $files['name'][$key] ?? null;
            $temporaryName = $files['tmp_name'][$key] ?? null;
            $error = $files['error'][$key] ?? null;
            $size = $files['size'][$key] ?? null;
            if (!is_string($name) || !is_string($temporaryName) || !is_int($error) || !is_int($size)) {
                continue;
            }
            $normalized[$position] = [
                'name' => basename(str_replace('\\', '/', $name)),
                'type' => is_string($files['type'][$key] ?? null) ? $files['type'][$key] : '',
                'tmp_name' => $temporaryName,
                'error' => $error,
                'size' => $size,
            ];
        }

        return $normalized;
    }

    public static function delete(?string $relativePath): void
    {
        if ($relativePath === null || $relativePath === '') {
            return;
        }
        $uploadDir = realpath(self::config()['dir']);
        $filePath = realpath(self::config()['dir'] . '/' . ltrim($relativePath, '/'));
        if ($uploadDir === false || $filePath === false || !is_file($filePath)) {
            return;
        }
        if (str_starts_with($filePath, $uploadDir . DIRECTORY_SEPARATOR)) {
            unlink($filePath);
        }
    }

    private static function store(?array $file, string $group, string $kind): ?string
    {
        if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $uploadConfig = self::config();
        if ($file['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'])) {
            throw new RuntimeException('Tải tệp thất bại.');
        }
        if ($file['size'] > $uploadConfig['max_size']) {
            throw new RuntimeException('Tệp vượt quá ' . (int) ($uploadConfig['max_size'] / 1024 / 1024) . 'MB.');
        }

        $mimeType = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
        $extension = $uploadConfig[$kind][$mimeType] ?? null;
        if ($extension === null) {
            throw new RuntimeException('Định dạng tệp không được hỗ trợ.');
        }
        if (str_starts_with((string) $mimeType, 'image/') && @getimagesize($file['tmp_name']) === false) {
            throw new RuntimeException('Tệp ảnh không hợp lệ.');
        }

        $safeGroup = preg_replace('/[^a-z0-9-]/', '', strtolower($group)) ?: 'khac';
        $targetDir = $uploadConfig['dir'] . '/' . $safeGroup;
        if (!is_dir($targetDir) && !mkdir($targetDir, 0775, true) && !is_dir($targetDir)) {
            throw new RuntimeException('Không tạo được thư mục lưu tệp.');
        }

        $fileName = bin2hex(random_bytes(8)) . '.' . $extension;
        if (!move_uploaded_file($file['tmp_name'], $targetDir . '/' . $fileName)) {
            throw new RuntimeException('Không lưu được tệp.');
        }

        return $safeGroup . '/' . $fileName;
    }

    private static function uniqueKey(array $existing, string $key): string
    {
        $uniqueKey = $key;
        $suffix = 2;
        while (array_key_exists($uniqueKey, $existing)) {
            $uniqueKey = $key . ' (' . $suffix . ')';
            $suffix++;
        }

        return $uniqueKey;
    }

    private static function config(): array
    {
        return (require APP_PATH . '/config/config.php')['upload'];
    }
}
