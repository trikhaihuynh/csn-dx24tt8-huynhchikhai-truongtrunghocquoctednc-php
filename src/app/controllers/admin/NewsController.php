<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Paginator;
use App\Core\Upload;
use App\Core\Validator;
use App\Models\News;
use RuntimeException;

final class NewsController extends AdminController
{
    private const PER_PAGE = 15;
    private const KEYWORD_MAX_LENGTH = 200;
    private const UPLOAD_GROUP = 'tin-tuc';
    private const INDEX_PATH = '/admin/tin-tuc';

    private const RULES = [
        'tieu_de' => 'required|max:200',
        'tom_tat' => 'max:500',
        'noi_dung' => 'required',
        'trang_thai' => 'required|in:nhap,cong_khai',
        'ngay_dang' => 'date',
    ];

    private const LABELS = [
        'tieu_de' => 'Tiêu đề',
        'tom_tat' => 'Tóm tắt',
        'noi_dung' => 'Nội dung',
        'trang_thai' => 'Trạng thái',
        'ngay_dang' => 'Ngày đăng',
    ];

    public function index(): void
    {
        $news = new News();
        $keyword = mb_substr(trim($this->queryString('q')), 0, self::KEYWORD_MAX_LENGTH);
        $status = $this->queryString('trang_thai');
        $status = array_key_exists($status, News::STATUSES) ? $status : null;
        $page = (int) $this->queryString('page', '1');

        $pager = Paginator::make($news->countSearch($keyword, $status), $page, self::PER_PAGE);

        $this->view('admin/news/index', [
            'title' => 'Quản lý tin tức',
            'items' => $news->search($keyword, $status, $pager['limit'], $pager['offset']),
            'pager' => $pager,
            'keyword' => $keyword,
            'status' => $status,
            'statuses' => News::STATUSES,
            'paginationPath' => self::INDEX_PATH,
        ]);
    }

    public function create(): void
    {
        $this->view('admin/news/form', [
            'title' => 'Thêm tin tức',
            'item' => null,
            'isPublished' => false,
            'formAction' => url(self::INDEX_PATH . '/them'),
            'statuses' => News::STATUSES,
        ]);
    }

    public function store(): void
    {
        Csrf::check();
        $formPath = self::INDEX_PATH . '/them';
        $input = $this->validateInput($formPath);
        $imagePath = $this->uploadImage($formPath);

        $news = new News();
        $news->create([
            'tieu_de' => $input['tieu_de'],
            'slug' => $news->uniqueSlug(slugify($input['tieu_de'])),
            'tom_tat' => $input['tom_tat'] !== '' ? $input['tom_tat'] : null,
            'noi_dung' => $input['noi_dung'],
            'hinh_dai_dien' => $imagePath,
            'ngay_dang' => $this->normalizePublishedAt($input['ngay_dang']) ?? date('Y-m-d H:i:s'),
            'trang_thai' => $input['trang_thai'],
            'nguoi_dung_id' => (int) Auth::user()['id'],
        ]);

        $this->flash('success', 'Đã thêm tin tức.');
        $this->redirect(self::INDEX_PATH);
    }

    public function edit(int $id): void
    {
        $item = (new News())->find($id);
        if ($item === null) {
            $this->notFound();
        }

        $this->view('admin/news/form', [
            'title' => 'Sửa tin tức',
            'item' => $item,
            'isPublished' => News::isPublished($item),
            'formAction' => url(self::INDEX_PATH . '/' . $id . '/sua'),
            'statuses' => News::STATUSES,
        ]);
    }

    public function update(int $id): void
    {
        Csrf::check();
        $news = new News();
        $item = $news->find($id);
        if ($item === null) {
            $this->notFound();
        }

        $formPath = self::INDEX_PATH . '/' . $id . '/sua';
        $input = $this->validateInput($formPath);
        $imagePath = $this->uploadImage($formPath);

        $data = [
            'tieu_de' => $input['tieu_de'],
            'tom_tat' => $input['tom_tat'] !== '' ? $input['tom_tat'] : null,
            'noi_dung' => $input['noi_dung'],
            'ngay_dang' => $this->normalizePublishedAt($input['ngay_dang']) ?? $item['ngay_dang'],
            'trang_thai' => $input['trang_thai'],
        ];
        if (trim((string) $item['slug']) === '') {
            $data['slug'] = $news->uniqueSlug(slugify($input['tieu_de']), $id);
        }
        if ($imagePath !== null) {
            $data['hinh_dai_dien'] = $imagePath;
        }

        $news->update($id, $data);
        if ($imagePath !== null) {
            Upload::delete($item['hinh_dai_dien']);
        }

        $this->flash('success', 'Đã cập nhật tin tức.');
        $this->redirect(self::INDEX_PATH);
    }

    public function destroy(int $id): void
    {
        Csrf::check();
        $news = new News();
        $item = $news->find($id);
        if ($item === null) {
            $this->notFound();
        }

        $news->delete($id);
        Upload::delete($item['hinh_dai_dien']);

        $this->flash('success', 'Đã xóa tin tức "' . $item['tieu_de'] . '".');
        $this->redirect(self::INDEX_PATH);
    }

    private function validateInput(string $formPath): array
    {
        $input = $this->formInput();
        $validator = Validator::make($input, self::RULES, self::LABELS);
        if ($validator->fails()) {
            $this->withErrors($validator->errors(), $this->oldInput($input), $formPath);
        }

        return $validator->validated();
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

    private function normalizePublishedAt(string $publishedAt): ?string
    {
        if ($publishedAt === '') {
            return null;
        }
        $timestamp = strtotime(str_replace('T', ' ', $publishedAt));

        return $timestamp === false ? null : date('Y-m-d H:i:s', $timestamp);
    }

    private function queryString(string $key, string $default = ''): string
    {
        $value = $this->query($key, $default);

        return is_string($value) ? $value : $default;
    }
}
