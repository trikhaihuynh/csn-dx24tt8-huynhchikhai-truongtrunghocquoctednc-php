<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Paginator;
use App\Core\Validator;
use App\Models\User;
use PDOException;

final class UserController extends AdminController
{
    private const PER_PAGE = 15;
    private const KEYWORD_MAX_LENGTH = 150;
    private const INDEX_PATH = '/admin/tai-khoan';
    private const PASSWORD_MIN_LENGTH = 8;
    private const PASSWORD_MAX_BYTES = 72;
    private const DUPLICATE_KEY_SQLSTATE = '23000';

    private const RULES = [
        'ho_ten' => 'required|max:100',
        'email' => 'required|email|max:150',
        'vai_tro' => 'required|in:admin,bien_tap',
        'trang_thai' => 'required|in:0,1',
    ];

    private const LABELS = [
        'ho_ten' => 'Họ tên',
        'email' => 'Email',
        'vai_tro' => 'Vai trò',
        'trang_thai' => 'Trạng thái',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->requireRole('admin');
    }

    public function index(): void
    {
        $users = new User();
        $keyword = mb_substr(trim($this->queryString('q')), 0, self::KEYWORD_MAX_LENGTH);
        $page = (int) $this->queryString('page', '1');

        $pager = Paginator::make($users->countSearch($keyword), $page, self::PER_PAGE);

        $this->view('admin/users/index', [
            'title' => 'Quản lý tài khoản',
            'items' => $users->search($keyword, $pager['limit'], $pager['offset']),
            'pager' => $pager,
            'keyword' => $keyword,
            'roles' => User::ROLES,
            'statuses' => User::STATUSES,
            'currentUserId' => $this->currentUserId(),
            'paginationPath' => self::INDEX_PATH,
        ]);
    }

    public function create(): void
    {
        $this->view('admin/users/form', [
            'title' => 'Thêm tài khoản',
            'item' => null,
            'isSelf' => false,
            'formAction' => url(self::INDEX_PATH . '/them'),
            'roles' => User::ROLES,
            'statuses' => User::STATUSES,
            'passwordMinLength' => self::PASSWORD_MIN_LENGTH,
        ]);
    }

    public function store(): void
    {
        Csrf::check();
        $formPath = self::INDEX_PATH . '/them';
        $input = $this->validateInput($formPath, null, true);
        $data = [
            'ho_ten' => $input['ho_ten'],
            'email' => $input['email'],
            'mat_khau' => password_hash($this->rawPassword(), PASSWORD_DEFAULT),
            'vai_tro' => $input['vai_tro'],
            'trang_thai' => (int) $input['trang_thai'],
        ];

        $this->saveOrFailOnDuplicateEmail($formPath, static function (User $users) use ($data): void {
            $users->create($data);
        });

        $this->flash('success', 'Đã thêm tài khoản ' . $input['email'] . '.');
        $this->redirect(self::INDEX_PATH);
    }

    public function edit(int $id): void
    {
        $item = (new User())->find($id);
        if ($item === null) {
            $this->notFound();
        }
        unset($item['mat_khau']);

        $this->view('admin/users/form', [
            'title' => 'Sửa tài khoản',
            'item' => $item,
            'isSelf' => $id === $this->currentUserId(),
            'formAction' => url(self::INDEX_PATH . '/' . $id . '/sua'),
            'roles' => User::ROLES,
            'statuses' => User::STATUSES,
            'passwordMinLength' => self::PASSWORD_MIN_LENGTH,
        ]);
    }

    public function update(int $id): void
    {
        Csrf::check();
        $item = (new User())->find($id);
        if ($item === null) {
            $this->notFound();
        }

        $formPath = self::INDEX_PATH . '/' . $id . '/sua';
        $input = $this->validateInput($formPath, $id, false);
        if ($id === $this->currentUserId()) {
            $this->rejectSelfDemotion($input, $item, $formPath);
        }

        $data = [
            'ho_ten' => $input['ho_ten'],
            'email' => $input['email'],
            'vai_tro' => $input['vai_tro'],
            'trang_thai' => (int) $input['trang_thai'],
        ];
        $password = $this->rawPassword();
        if ($password !== '') {
            $data['mat_khau'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->saveOrFailOnDuplicateEmail($formPath, static function (User $users) use ($id, $data): void {
            $users->update($id, $data);
        });

        $this->flash('success', 'Đã cập nhật tài khoản ' . $input['email'] . '.');
        $this->redirect(self::INDEX_PATH);
    }

    public function destroy(int $id): void
    {
        Csrf::check();
        $users = new User();
        $item = $users->find($id);
        if ($item === null) {
            $this->notFound();
        }

        if ($id === $this->currentUserId()) {
            $this->flash('error', 'Không thể xóa tài khoản đang đăng nhập.');
            $this->redirect(self::INDEX_PATH);
        }

        $users->delete($id);

        $this->flash('success', 'Đã xóa tài khoản ' . $item['email'] . '.');
        $this->redirect(self::INDEX_PATH);
    }

    private function validateInput(string $formPath, ?int $ignoreId, bool $isPasswordRequired): array
    {
        $input = $this->formInput();
        $validator = Validator::make($input, self::RULES, self::LABELS);
        $errors = $validator->errors();
        $validated = $validator->validated();

        if (!isset($errors['email']) && (new User())->emailExists($validated['email'], $ignoreId)) {
            $errors['email'] = 'Email này đã được dùng cho tài khoản khác.';
        }
        $passwordError = $this->passwordError($isPasswordRequired);
        if ($passwordError !== null) {
            $errors['mat_khau'] = $passwordError;
        }

        if ($errors !== []) {
            $this->withErrors($errors, $this->oldInput($input), $formPath);
        }

        return $validated;
    }

    private function passwordError(bool $isRequired): ?string
    {
        $password = $_POST['mat_khau'] ?? '';
        $confirmation = $_POST['mat_khau_confirmation'] ?? '';
        if (!is_string($password) || !is_string($confirmation)) {
            return 'Mật khẩu không hợp lệ.';
        }
        if ($password === '' && $confirmation === '' && !$isRequired) {
            return null;
        }
        if (trim($password) === '') {
            return 'Vui lòng nhập Mật khẩu.';
        }
        if (mb_strlen($password) < self::PASSWORD_MIN_LENGTH) {
            return 'Mật khẩu tối thiểu ' . self::PASSWORD_MIN_LENGTH . ' ký tự.';
        }
        if (strlen($password) > self::PASSWORD_MAX_BYTES) {
            return 'Mật khẩu quá dài (tối đa ' . self::PASSWORD_MAX_BYTES . ' ký tự không dấu).';
        }
        if (!hash_equals($password, $confirmation)) {
            return 'Mật khẩu xác nhận không khớp.';
        }

        return null;
    }

    private function rejectSelfDemotion(array $input, array $item, string $formPath): void
    {
        $errors = [];
        if ($input['vai_tro'] !== $item['vai_tro']) {
            $errors['vai_tro'] = 'Không thể tự đổi vai trò của chính mình.';
        }
        if ($input['trang_thai'] !== '1') {
            $errors['trang_thai'] = 'Không thể tự khóa tài khoản của chính mình.';
        }
        if ($errors === []) {
            return;
        }

        $this->flash('error', 'Không thể tự khóa hoặc đổi vai trò của tài khoản đang đăng nhập.');
        $this->withErrors($errors, $this->oldInput($this->formInput()), $formPath);
    }

    private function saveOrFailOnDuplicateEmail(string $formPath, callable $save): void
    {
        try {
            $save(new User());
        } catch (PDOException $exception) {
            if ((string) $exception->getCode() !== self::DUPLICATE_KEY_SQLSTATE) {
                throw $exception;
            }
            $this->withErrors(
                ['email' => 'Email này đã được dùng cho tài khoản khác.'],
                $this->oldInput($this->formInput()),
                $formPath
            );
        }
    }

    private function rawPassword(): string
    {
        $password = $_POST['mat_khau'] ?? '';

        return is_string($password) ? $password : '';
    }

    private function formInput(): array
    {
        $input = [];
        foreach (array_keys(self::RULES) as $field) {
            $input[$field] = $_POST[$field] ?? '';
        }

        return $input;
    }

    private function oldInput(array $input): array
    {
        return array_map(static fn (mixed $value): string => is_string($value) ? $value : '', $input);
    }

    private function currentUserId(): int
    {
        return (int) (Auth::user()['id'] ?? 0);
    }

    private function queryString(string $key, string $default = ''): string
    {
        $value = $this->query($key, $default);

        return is_string($value) ? $value : $default;
    }
}
