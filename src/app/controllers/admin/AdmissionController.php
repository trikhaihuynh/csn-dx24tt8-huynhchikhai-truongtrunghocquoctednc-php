<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Csrf;
use App\Core\Paginator;
use App\Core\Upload;
use App\Core\Validator;
use App\Models\Admission;

final class AdmissionController extends AdminController
{
    private const PER_PAGE = 15;
    private const KEYWORD_MAX_LENGTH = 150;
    private const INDEX_PATH = '/admin/dang-ky';

    private const RULES = [
        'trang_thai' => 'required|in:moi,dang_xu_ly,da_lien_he,tu_choi',
        'ghi_chu_admin' => 'max:2000',
    ];

    private const LABELS = [
        'trang_thai' => 'Trạng thái',
        'ghi_chu_admin' => 'Ghi chú xử lý',
    ];

    public function index(): void
    {
        $admissions = new Admission();
        $keyword = mb_substr(trim($this->queryString('q')), 0, self::KEYWORD_MAX_LENGTH);
        $status = $this->queryString('trang_thai');
        $status = array_key_exists($status, Admission::STATUSES) ? $status : null;
        $page = (int) $this->queryString('page', '1');

        $pager = Paginator::make($admissions->countSearch($keyword, $status), $page, self::PER_PAGE);

        $this->view('admin/admissions/index', [
            'title' => 'Đăng ký nhập học',
            'items' => $admissions->search($keyword, $status, $pager['limit'], $pager['offset']),
            'pager' => $pager,
            'keyword' => $keyword,
            'status' => $status,
            'statuses' => Admission::STATUSES,
            'paginationPath' => self::INDEX_PATH,
        ]);
    }

    public function show(int $id): void
    {
        $item = (new Admission())->findWithProgram($id);
        if ($item === null) {
            $this->notFound();
        }

        $this->view('admin/admissions/show', [
            'title' => 'Hồ sơ đăng ký #' . $id,
            'item' => $item,
            'transcriptUrl' => $this->transcriptUrl($item['tep_hoc_ba']),
            'statuses' => Admission::STATUSES,
            'genders' => Admission::GENDERS,
            'formAction' => url(self::INDEX_PATH . '/' . $id),
            'deleteAction' => url(self::INDEX_PATH . '/' . $id . '/xoa'),
        ]);
    }

    public function update(int $id): void
    {
        Csrf::check();
        $admissions = new Admission();
        if ($admissions->find($id) === null) {
            $this->notFound();
        }

        $input = $this->formInput();
        $validator = Validator::make($input, self::RULES, self::LABELS);
        if ($validator->fails()) {
            $this->withErrors($validator->errors(), $this->oldInput($input), self::INDEX_PATH . '/' . $id);
        }

        $validated = $validator->validated();
        $admissions->updateStatus(
            $id,
            $validated['trang_thai'],
            $validated['ghi_chu_admin'] !== '' ? $validated['ghi_chu_admin'] : null
        );

        $statusLabel = Admission::STATUSES[$validated['trang_thai']];
        $this->flash('success', 'Đã cập nhật hồ sơ, trạng thái: ' . $statusLabel . '.');
        $this->redirect(self::INDEX_PATH . '/' . $id);
    }

    public function destroy(int $id): void
    {
        Csrf::check();
        $admissions = new Admission();
        $item = $admissions->find($id);
        if ($item === null) {
            $this->notFound();
        }

        $admissions->delete($id);
        Upload::delete($item['tep_hoc_ba']);

        $this->flash('success', 'Đã xóa hồ sơ đăng ký của "' . $item['ho_ten'] . '".');
        $this->redirect(self::INDEX_PATH);
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

    private function transcriptUrl(?string $relativePath): ?string
    {
        $relativePath = ltrim((string) $relativePath, '/');
        if ($relativePath === '' || !is_file(PUBLIC_PATH . '/uploads/' . $relativePath)) {
            return null;
        }

        return upload_url($relativePath);
    }

    private function queryString(string $key, string $default = ''): string
    {
        $value = $this->query($key, $default);

        return is_string($value) ? $value : $default;
    }
}
