<?php

declare(strict_types=1);

namespace App\Core;

use LogicException;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionType;

final class Router
{
    private array $routes = ['GET' => [], 'POST' => []];

    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        $method = strtoupper($method);
        if ($method === 'HEAD') {
            $method = 'GET';
        }

        $path = self::normalizePath($uri);

        foreach ($this->routes[$method] ?? [] as $pattern => [$controllerClass, $action]) {
            $regex = '#^' . preg_replace('#\{([a-z_]+)\}#', '(?P<$1>[^/]+)', $pattern) . '$#';
            if (preg_match($regex, $path, $matches)) {
                $params = array_values(array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY));
                $this->callAction($controllerClass, $action, $params);
                return;
            }
        }

        $this->renderNotFound();
    }

    public static function normalizePath(string $uri): string
    {
        $pathWithoutQuery = explode('?', $uri, 2)[0];
        $pathWithSingleSlashes = preg_replace('#/+#', '/', $pathWithoutQuery) ?? $pathWithoutQuery;

        return '/' . trim($pathWithSingleSlashes, '/');
    }

    private function callAction(string $controllerClass, string $action, array $params): void
    {
        if (!method_exists($controllerClass, $action)) {
            throw new LogicException("Không tìm thấy action {$controllerClass}::{$action}.");
        }

        $arguments = [];
        $reflection = new ReflectionMethod($controllerClass, $action);
        foreach ($reflection->getParameters() as $position => $parameter) {
            if (!array_key_exists($position, $params)) {
                break;
            }
            $value = $params[$position];
            if ($this->expectsInteger($parameter->getType())) {
                if (!$this->isIntegerId($value)) {
                    $this->renderNotFound();
                    return;
                }
                $value = (int) $value;
            }
            $arguments[] = $value;
        }

        $controller = new $controllerClass();
        $controller->$action(...$arguments);
    }

    private function expectsInteger(?ReflectionType $type): bool
    {
        return $type instanceof ReflectionNamedType && $type->getName() === 'int';
    }

    private function isIntegerId(string $value): bool
    {
        return ctype_digit($value) && strlen($value) <= 10;
    }

    private function renderNotFound(): void
    {
        http_response_code(404);
        View::render('errors/404', ['title' => 'Không tìm thấy trang'], 'public');
    }
}
