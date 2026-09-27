<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Router;
use App\Core\Validator;
use App\Models\User;

final class AuthController extends Controller
{
    private const RULES = ['email' => 'required|email|max:150', 'mat_khau' => 'required|max:255'];
    private const LABELS = ['email' => 'Email', 'mat_khau' => 'Mật khẩu'];
    private const LOGIN_FAILED_MESSAGE = 'Email hoặc mật khẩu không đúng.';
    private const TIMING_SAFE_DUMMY_HASH = '$2y$10$QZHltCB1QUEiJkAS1Oy8I.3oHRthYK40bqst1PhjiuCtlzPt.1Gr6';
    private const DEFAULT_REDIRECT = '/admin';

    protected string $layout = 'admin';

    public function showLogin(): void
    {
        if (Auth::check()) {
            $this->redirect(self::DEFAULT_REDIRECT);
        }

        $this->view('admin/login', ['title' => 'Đăng nhập quản trị']);
    }

    public function login(): void
    {
        Csrf::check();

        $validator = Validator::make($_POST, self::RULES, self::LABELS);
        if ($validator->fails()) {
            $this->withErrors($validator->errors(), ['email' => $this->post('email')], '/admin/login');
        }

        $credentials = $validator->validated();
        $rawPassword = is_string($_POST['mat_khau'] ?? null) ? $_POST['mat_khau'] : '';
        $user = (new User())->findActiveByEmail($credentials['email']);
        $passwordHash = $user['mat_khau'] ?? self::TIMING_SAFE_DUMMY_HASH;
        $passwordMatches = password_verify($rawPassword, $passwordHash);

        if ($user === null || !$passwordMatches) {
            $this->flash('error', self::LOGIN_FAILED_MESSAGE);
            $this->withErrors([], ['email' => $credentials['email']], '/admin/login');
        }

        $redirectTo = $this->pullIntendedPath();
        Auth::login($user);
        $this->redirect($redirectTo);
    }

    public function logout(): void
    {
        Csrf::check();
        Auth::logout();
        unset($_SESSION['_intended']);
        $this->flash('success', 'Bạn đã đăng xuất.');
        $this->redirect('/admin/login');
    }

    private function pullIntendedPath(): string
    {
        $intendedUri = $_SESSION['_intended'] ?? '';
        unset($_SESSION['_intended']);
        if (!is_string($intendedUri) || $intendedUri === '') {
            return self::DEFAULT_REDIRECT;
        }

        $path = Router::normalizePath($intendedUri);
        $isAdminPath = $path === '/admin' || str_starts_with($path, '/admin/');
        if (!$isAdminPath || $path === '/admin/login' || $path === '/admin/logout') {
            return self::DEFAULT_REDIRECT;
        }

        $queryString = parse_url($intendedUri, PHP_URL_QUERY);

        return $path . (is_string($queryString) && $queryString !== '' ? '?' . $queryString : '');
    }
}
