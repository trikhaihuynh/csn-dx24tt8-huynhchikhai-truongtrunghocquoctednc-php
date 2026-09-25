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

        if (!$this->currentUserIsActive()) {
            Auth::logout();
            $this->flash('error', 'Tài khoản đã bị khóa.');
            $this->redirect('/admin/login');
        }
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

    private function currentUserIsActive(): bool
    {
        $userId = (int) (Auth::user()['id'] ?? 0);
        $storedUser = $userId > 0 ? (new User())->find($userId) : null;

        return $storedUser !== null && (int) $storedUser['trang_thai'] === 1;
    }
}
