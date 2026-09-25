<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Csrf;
use App\Core\Paginator;
use App\Core\Upload;
use App\Core\Validator;
use App\Models\Program;
use RuntimeException;

final class ProgramController extends AdminController
{
    private const PER_PAGE = 15;
    private const KEYWORD_MAX_LENGTH = 150;
    private const UPLOAD_GROUP = 'chuong-trinh';
    private const INDEX_PATH = '/admin/chuong-trinh';
    private const SORT_ORDER_MAX_DIGITS = 6;

    private const RULES = [
        'ten' => 'required|max:150',
        'mo_ta' => 'max:500',
        'noi_dung' => '',
        'thu_tu' => 'numeric',
        'trang_thai' => 'required|in:0,1',
    ];

    private const LABELS = [
        'ten' => 'Tên chương trình',
        'mo_ta' => 'Mô tả ngắn',
        'noi_dung' => 'Nội dung',
        'thu_tu' => 'Thứ tự',
        'trang_thai' => 'Trạng thái',
    ];

    public function index(): void
    {
        $programs = new Program();
        $keyword = mb_substr(trim($this->queryString('q')), 0, self::KEYWORD_MAX_LENGTH);
        $status = $this->queryString('trang_thai');
        $status = array_key_exists($status, Program::STATUSES) ? $status : null;
        $statusFilter = $status === null ? null : (int) $status;
        $page = (int) $this->queryString('page', '1');

        $pager = Paginator::make($programs->countSearch($keyword, $statusFilter), $page, self::PER_PAGE);

        $this->view('admin/programs/index', [
            'title' => 'Quản lý chương trình đào tạo',
            'items' => $programs->search($keyword, $statusFilter, $pager['limit'], $pager['offset']),
            'pager' => $pager,
            'keyword' => $keyword,
            'status' => $status,
            'statuses' => Program::STATUSES,
            'paginationPath' => self::INDEX_PATH,
        ]);
    }

    public function create(): void
    {
        $this->view('admin/programs/form', [
            'title' => 'Thêm chương trình',
            'item' => null,
            'formAction' => url(self::INDEX_PATH . '/them'),
            'statuses' => Program::STATUSES,
        ]);
    }

    public function store(): void
    {
        Csrf::check();
        $formPath = self::INDEX_PATH . '/them';
        $input = $this->validateInput($formPath);
        $imagePath = $this->uploadImage($formPath);

        $programs = new Program();
        $programs->create([
            'ten' => $input['ten'],
            'slug' => $programs->uniqueSlug(slugify($input['ten'])),
            'mo_ta' => $input['mo_ta'] !== '' ? $input['mo_ta'] : null,
            'noi_dung' => $input['noi_dung'] !== '' ? $input['noi_dung'] : null,
            'hinh_dai_dien' => $imagePath,
            'thu_tu' => (int) $input['thu_tu'],
            'trang_thai' => (int) $input['trang_thai'],
        ]);

        $this->flash('success', 'Đã thêm chương trình đào tạo.');
        $this->redirect(self::INDEX_PATH);
    }

    public function edit(int $id): void
    {
        $item = (new Program())->find($id);
        if ($item === null) {
            $this->notFound();
        }

        $this->view('admin/programs/form', [
            'title' => 'Sửa chương trình',
            'item' => $item,
            'formAction' => url(self::INDEX_PATH . '/' . $id . '/sua'),
            'statuses' => Program::STATUSES,
        ]);
    }

    public function update(int $id): void
    {
        Csrf::check();
        $programs = new Program();
        $item = $programs->find($id);
        if ($item === null) {
            $this->notFound();
        }

        $formPath = self::INDEX_PATH . '/' . $id . '/sua';
        $input = $this->validateInput($formPath);
        $imagePath = $this->uploadImage($formPath);

        $data = [
            'ten' => $input['ten'],
            'mo_ta' => $input['mo_ta'] !== '' ? $input['mo_ta'] : null,
            'noi_dung' => $input['noi_dung'] !== '' ? $input['noi_dung'] : null,
            'thu_tu' => (int) $input['thu_tu'],
            'trang_thai' => (int) $input['trang_thai'],
        ];
        if (trim((string) $item['slug']) === '') {
            $data['slug'] = $programs->uniqueSlug(slugify($input['ten']), $id);
        }
        if ($imagePath !== null) {
            $data['hinh_dai_dien'] = $imagePath;
        }

        $programs->update($id, $data);
        if ($imagePath !== null) {
            Upload::delete($item['hinh_dai_dien']);
        }

        $this->flash('success', 'Đã cập nhật chương trình đào tạo.');
        $this->redirect(self::INDEX_PATH);
    }

    public function destroy(int $id): void
    {
        Csrf::check();
        $programs = new Program();
        $item = $programs->find($id);
        if ($item === null) {
            $this->notFound();
        }

        $admissionCount = $programs->countAdmissions($id);
        $programs->delete($id);
        Upload::delete($item['hinh_dai_dien']);

        $message = 'Đã xóa chương trình "' . $item['ten'] . '".';
        if ($admissionCount > 0) {
            $message .= ' ' . $admissionCount . ' hồ sơ đăng ký không còn liên kết chương trình.';
        }
        $this->flash('success', $message);
        $this->redirect(self::INDEX_PATH);
    }

    private function validateInput(string $formPath): array
    {
        $input = $this->formInput();
        $validator = Validator::make($input, self::RULES, self::LABELS);
        $errors = $validator->errors();
        $validated = $validator->validated();
        if (!isset($errors['thu_tu']) && !$this->isValidSortOrder($validated['thu_tu'])) {
            $errors['thu_tu'] = self::LABELS['thu_tu'] . ' phải là số nguyên từ 0 đến 999999.';
        }
        if ($errors !== []) {
            $this->withErrors($errors, $this->oldInput($input), $formPath);
        }

        return $validated;
    }

    private function isValidSortOrder(string $sortOrder): bool
    {
        return $sortOrder === ''
            || (ctype_digit($sortOrder) && strlen($sortOrder) <= self::SORT_ORDER_MAX_DIGITS);
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

    private function uploadImage(string $formPath): ?string
    {
        try {
            return Upload::image($_FILES['hinh_dai_dien'] ?? null, self::UPLOAD_GROUP);
        } catch (RuntimeException $exception) {
            $this->withErrors(
                ['hinh_dai_dien' => $exception->getMessage()],
                $this->oldInput($this->formInput()),
                $formPath
            );
        }
    }

    private function queryString(string $key, string $default = ''): string
    {
        $value = $this->query($key, $default);

        return is_string($value) ? $value : $default;
    }
}
