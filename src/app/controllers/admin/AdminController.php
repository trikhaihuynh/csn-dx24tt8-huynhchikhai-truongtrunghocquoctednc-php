<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\User;

abstract class AdminController extends Controller
{
    protected string $layout = 'admin';

    public function __construct()
    {
        if (!Auth::check()) {
            if (!$this->isPost()) {
                $_SESSION['_intended'] = $_SERVER['REQUEST_URI'] ?? '/admin';
            }
            $this->redirect('/admin/login');
        }

        $storedUser = $this->storedCurrentUser();
        if ($storedUser === null || (int) $storedUser['trang_thai'] !== 1) {
            Auth::logout();
            $this->flash('error', 'Tài khoản đã bị khóa.');
            $this->redirect('/admin/login');
        }
        Auth::refresh($storedUser);
    }

    protected function requireRole(string $role): void
    {
        if (!Auth::is($role)) {
            http_response_code(403);
            $this->view('errors/403', [
                'title' => 'Không có quyền truy cập',
                'message' => 'Tài khoản của bạn không có quyền thực hiện chức năng này.',
            ]);
            exit;
        }
    }

    private function storedCurrentUser(): ?array
    {
        $userId = (int) (Auth::user()['id'] ?? 0);

        return $userId > 0 ? (new User())->find($userId) : null;
    }
}
