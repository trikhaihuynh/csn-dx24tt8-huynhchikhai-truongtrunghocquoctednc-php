<?php

declare(strict_types=1);

namespace App\Core;

final class Auth
{
    public static function check(): bool
    {
        return isset($_SESSION['auth_user']);
    }

    public static function user(): ?array
    {
        return $_SESSION['auth_user'] ?? null;
    }

    public static function is(string $role): bool
    {
        return (self::user()['vai_tro'] ?? null) === $role;
    }

    public static function login(array $userRow): void
    {
        unset($userRow['mat_khau']);
        session_regenerate_id(true);
        $_SESSION['auth_user'] = $userRow;
    }

    public static function refresh(array $userRow): void
    {
        if (!self::check()) {
            return;
        }
        foreach (['ho_ten', 'email', 'vai_tro'] as $field) {
            if (array_key_exists($field, $userRow)) {
                $_SESSION['auth_user'][$field] = $userRow[$field];
            }
        }
    }

    public static function logout(): void
    {
        unset($_SESSION['auth_user']);
        session_regenerate_id(true);
    }
}
