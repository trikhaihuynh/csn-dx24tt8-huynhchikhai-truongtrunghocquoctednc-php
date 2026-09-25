<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Csrf;
use App\Core\Validator;
use App\Models\Setting;

final class SettingController extends AdminController
{
    private const INDEX_PATH = '/admin/cai-dat';
    private const VALUE_MAX_LENGTH = 2000;
    private const DISPLAY_ORDER = [
        'ten_truong',
        'ten_truong_en',
        'slogan',
        'gioi_thieu',
        'dia_chi',
        'dien_thoai',
        'email',
        'facebook',
        'youtube',
    ];
    private const EXTRA_RULES = [
        'ten_truong' => 'required',
        'email' => 'email',
        'facebook' => 'url',
        'youtube' => 'url',
    ];
    private const INPUT_TYPES = [
        'gioi_thieu' => 'textarea',
        'email' => 'email',
        'facebook' => 'url',
        'youtube' => 'url',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->requireRole('admin');
    }

    public function edit(): void
    {
        $this->view('admin/settings/form', [
            'title' => 'Cài đặt thông tin trường',
            'fields' => $this->formFields((new Setting())->allWithDescriptions()),
            'formAction' => url(self::INDEX_PATH),
            'valueMaxLength' => self::VALUE_MAX_LENGTH,
        ]);
    }

    public function update(): void
    {
        Csrf::check();
        $input = [];
        $rules = [];
        $labels = [];
        foreach ((new Setting())->allWithDescriptions() as $row) {
            $key = $row['khoa'];
            if (!array_key_exists($key, $_POST)) {
                continue;
            }
            $input[$key] = $_POST[$key];
            $rules[$key] = $this->rulesFor($key);
            $labels[$key] = $this->shortLabel($row);
        }

        if ($input === []) {
            $this->flash('error', 'Không có thông tin nào được gửi để lưu.');
            $this->redirect(self::INDEX_PATH);
        }

        $validator = Validator::make($input, $rules, $labels);
        if ($validator->fails()) {
            $this->withErrors($validator->errors(), $this->oldInput($input), self::INDEX_PATH);
        }

        (new Setting())->saveMany($validator->validated());

        $this->flash('success', 'Đã lưu cài đặt thông tin trường.');
        $this->redirect(self::INDEX_PATH);
    }

    private function formFields(array $rows): array
    {
        $sortKey = fn (array $row): array => [$this->displayPosition($row['khoa']), $row['khoa']];
        usort($rows, static fn (array $first, array $second): int => $sortKey($first) <=> $sortKey($second));

        return array_map(fn (array $row): array => [
            'key' => $row['khoa'],
            'value' => (string) $row['gia_tri'],
            'label' => trim((string) $row['mo_ta']) !== '' ? $row['mo_ta'] : $row['khoa'],
            'type' => self::INPUT_TYPES[$row['khoa']] ?? 'text',
            'required' => str_contains($this->rulesFor($row['khoa']), 'required'),
        ], $rows);
    }

    private function displayPosition(string $key): int
    {
        $position = array_search($key, self::DISPLAY_ORDER, true);

        return $position === false ? count(self::DISPLAY_ORDER) : $position;
    }

    private function rulesFor(string $key): string
    {
        $extraRule = self::EXTRA_RULES[$key] ?? '';

        return ($extraRule !== '' ? $extraRule . '|' : '') . 'max:' . self::VALUE_MAX_LENGTH;
    }

    private function shortLabel(array $row): string
    {
        $description = trim(explode(' - ', (string) $row['mo_ta'])[0]);

        return $description !== '' ? $description : $row['khoa'];
    }

    private function oldInput(array $input): array
    {
        return array_map(static fn (mixed $value): string => is_string($value) ? $value : '', $input);
    }
}
