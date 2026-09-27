<?php

declare(strict_types=1);

namespace App\Core;

use Throwable;

final class View
{
    public static function render(string $view, array $data = [], ?string $layout = 'public'): void
    {
        $data['errors'] = $_SESSION['_errors'] ?? [];
        unset($_SESSION['_errors']);
        extract($data, EXTR_SKIP);

        $bufferLevel = ob_get_level();
        try {
            ob_start();
            require APP_PATH . '/views/' . $view . '.php';
            $content = ob_get_clean();
            unset($_SESSION['_old']);

            if ($layout === null) {
                echo $content;
                return;
            }

            ob_start();
            require APP_PATH . '/views/layouts/' . $layout . '.php';
            echo ob_get_clean();
        } catch (Throwable $exception) {
            self::discardPartialOutput($bufferLevel);
            throw $exception;
        }
    }

    private static function discardPartialOutput(int $bufferLevel): void
    {
        while (ob_get_level() > $bufferLevel) {
            ob_end_clean();
        }
    }
}
