<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Csrf;
use App\Core\Paginator;
use App\Core\Upload;
use App\Core\Validator;
use App\Models\GalleryImage;
use RuntimeException;

final class GalleryController extends AdminController
{
    private const PER_PAGE = 20;
    private const KEYWORD_MAX_LENGTH = 200;
    private const TITLE_MAX_LENGTH = 200;
    private const ALBUM_MAX_LENGTH = 100;
    private const UPLOAD_GROUP = 'hinh-anh';
    private const INDEX_PATH = '/admin/hinh-anh';
    private const SORT_ORDER_MAX_DIGITS = 6;
    private const DEFAULT_TITLE = 'Hình ảnh';

    private const RULES = [
        'tieu_de' => 'max:200',
        'mo_ta' => 'max:500',
        'album' => 'max:100',
        'thu_tu' => 'numeric',
        'trang_thai' => 'required|in:0,1',
    ];

    private const LABELS = [
        'tieu_de' => 'Tiêu đề',
        'mo_ta' => 'Mô tả',
        'album' => 'Album',
        'thu_tu' => 'Thứ tự',
        'trang_thai' => 'Trạng thái',
    ];

    public function index(): void
    {
        $galleryImages = new GalleryImage();
        $keyword = mb_substr(trim($this->queryString('q')), 0, self::KEYWORD_MAX_LENGTH);
        $album = mb_substr(trim($this->queryString('album')), 0, self::ALBUM_MAX_LENGTH);
        $albumFilter = $album !== '' ? $album : null;
        $page = (int) $this->queryString('page', '1');

        $pager = Paginator::make($galleryImages->countSearch($keyword, $albumFilter), $page, self::PER_PAGE);

        $this->view('admin/gallery/index', [
            'title' => 'Quản lý hình ảnh',
            'items' => $galleryImages->search($keyword, $albumFilter, $pager['limit'], $pager['offset']),
            'pager' => $pager,
            'keyword' => $keyword,
            'album' => $album,
            'albums' => $this->albumOptions($galleryImages->albums()),
            'statuses' => GalleryImage::STATUSES,
            'paginationPath' => self::INDEX_PATH,
        ]);
    }

    public function create(): void
    {
        $this->view('admin/gallery/form', [
            'title' => 'Thêm hình ảnh',
            'item' => null,
            'formAction' => url(self::INDEX_PATH . '/them'),
            'statuses' => GalleryImage::STATUSES,
            'albums' => $this->albumOptions((new GalleryImage())->albums()),
        ]);
    }

    public function store(): void
    {
        Csrf::check();
        $formPath = self::INDEX_PATH . '/them';
        $input = $this->validateInput($formPath, false);

        $uploadedFiles = Upload::normalizeFiles($_FILES['hinh_anh'] ?? []);
        $selectedFiles = array_filter(
            $uploadedFiles,
            static fn (array $file): bool => $file['error'] !== UPLOAD_ERR_NO_FILE
        );
        if ($selectedFiles === []) {
            $this->withErrors(
                ['hinh_anh' => 'Vui lòng chọn ít nhất một ảnh.'],
                $this->oldInput($this->formInput()),
                $formPath
            );
        }

        $uploadResult = Upload::images($_FILES['hinh_anh'] ?? [], self::UPLOAD_GROUP);
        if ($uploadResult['saved'] === []) {
            $this->withErrors(
                ['hinh_anh' => 'Không có ảnh hợp lệ. ' . $this->describeFailures($uploadResult['errors'])],
                $this->oldInput($this->formInput()),
                $formPath
            );
        }

        $galleryImages = new GalleryImage();
        foreach ($uploadResult['saved'] as $index => $imagePath) {
            $galleryImages->create([
                'tieu_de' => $input['tieu_de'] !== ''
                    ? $input['tieu_de']
                    : $this->titleFromFileName($uploadedFiles[$index]['name'] ?? ''),
                'duong_dan' => $imagePath,
                'mo_ta' => $input['mo_ta'] !== '' ? $input['mo_ta'] : null,
                'album' => $this->normalizeAlbum($input['album']),
                'thu_tu' => (int) $input['thu_tu'],
                'trang_thai' => (int) $input['trang_thai'],
            ]);
        }

        $message = 'Đã thêm ' . count($uploadResult['saved']) . ' hình ảnh.';
        if ($uploadResult['errors'] !== []) {
            $this->flash('warning', $message . ' Bỏ qua ' . count($uploadResult['errors']) . ' tệp: '
                . $this->describeFailures($uploadResult['errors']));
        } else {
            $this->flash('success', $message);
        }
        $this->redirect(self::INDEX_PATH);
    }

    public function edit(int $id): void
    {
        $galleryImages = new GalleryImage();
        $item = $galleryImages->find($id);
        if ($item === null) {
            $this->notFound();
        }

        $this->view('admin/gallery/form', [
            'title' => 'Sửa hình ảnh',
            'item' => $item,
            'formAction' => url(self::INDEX_PATH . '/' . $id . '/sua'),
            'statuses' => GalleryImage::STATUSES,
            'albums' => $this->albumOptions($galleryImages->albums()),
        ]);
    }

    public function update(int $id): void
    {
        Csrf::check();
        $galleryImages = new GalleryImage();
        $item = $galleryImages->find($id);
        if ($item === null) {
            $this->notFound();
        }

        $formPath = self::INDEX_PATH . '/' . $id . '/sua';
        $input = $this->validateInput($formPath, true);
        $imagePath = $this->uploadReplacement($formPath);

        $data = [
            'tieu_de' => $input['tieu_de'],
            'mo_ta' => $input['mo_ta'] !== '' ? $input['mo_ta'] : null,
            'album' => $this->normalizeAlbum($input['album']),
            'thu_tu' => (int) $input['thu_tu'],
            'trang_thai' => (int) $input['trang_thai'],
        ];
        if ($imagePath !== null) {
            $data['duong_dan'] = $imagePath;
        }

        $galleryImages->update($id, $data);
        if ($imagePath !== null) {
            Upload::delete($item['duong_dan']);
        }

        $this->flash('success', 'Đã cập nhật hình ảnh.');
        $this->redirect(self::INDEX_PATH);
    }

    public function destroy(int $id): void
    {
        Csrf::check();
        $galleryImages = new GalleryImage();
        $item = $galleryImages->find($id);
        if ($item === null) {
            $this->notFound();
        }

        $galleryImages->delete($id);
        Upload::delete($item['duong_dan']);

        $this->flash('success', 'Đã xóa hình ảnh "' . $item['tieu_de'] . '".');
        $this->redirect(self::INDEX_PATH);
    }

    private function validateInput(string $formPath, bool $titleRequired): array
    {
        $input = $this->formInput();
        $rules = self::RULES;
        if ($titleRequired) {
            $rules['tieu_de'] = 'required|' . $rules['tieu_de'];
        }
        $validator = Validator::make($input, $rules, self::LABELS);
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

    private function normalizeAlbum(string $album): ?string
    {
        $albumSlug = trim(substr(slugify($album), 0, self::ALBUM_MAX_LENGTH), '-');

        return $albumSlug !== '' ? $albumSlug : null;
    }

    private function titleFromFileName(string $fileName): string
    {
        $title = trim(preg_replace('/[\s_]+/u', ' ', pathinfo($fileName, PATHINFO_FILENAME)) ?? '');

        return $title !== '' ? mb_substr($title, 0, self::TITLE_MAX_LENGTH) : self::DEFAULT_TITLE;
    }

    private function describeFailures(array $failures): string
    {
        $descriptions = [];
        foreach ($failures as $fileName => $reason) {
            $descriptions[] = $fileName . ' (' . rtrim($reason, '.') . ')';
        }

        return implode('; ', $descriptions) . '.';
    }

    private function uploadReplacement(string $formPath): ?string
    {
        $replacementFile = array_values(Upload::normalizeFiles($_FILES['hinh_anh'] ?? []))[0] ?? null;
        try {
            return Upload::image($replacementFile, self::UPLOAD_GROUP);
        } catch (RuntimeException $exception) {
            $this->withErrors(
                ['hinh_anh' => $exception->getMessage()],
                $this->oldInput($this->formInput()),
                $formPath
            );
        }
    }

    private function albumOptions(array $albums): array
    {
        $options = [];
        foreach ($albums as $album) {
            $options[(string) $album] = GalleryImage::albumLabel((string) $album);
        }

        return $options;
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

    private function queryString(string $key, string $default = ''): string
    {
        $value = $this->query($key, $default);

        return is_string($value) ? $value : $default;
    }
}
