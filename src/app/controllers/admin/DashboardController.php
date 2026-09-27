<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Admission;
use App\Models\GalleryImage;
use App\Models\News;
use App\Models\Program;

final class DashboardController extends AdminController
{
    private const LATEST_LIMIT = 5;
    private const RECENT_DAYS = 7;

    public function index(): void
    {
        $news = new News();
        $admission = new Admission();

        $this->view('admin/dashboard', [
            'title' => 'Trang chính',
            'stats' => [
                [
                    'key' => 'news',
                    'label' => 'Tin công khai',
                    'value' => $news->countPublished(),
                    'link' => '/admin/tin-tuc',
                ],
                [
                    'key' => 'programs',
                    'label' => 'Chương trình hiển thị',
                    'value' => (new Program())->countActive(),
                    'link' => '/admin/chuong-trinh',
                ],
                [
                    'key' => 'gallery',
                    'label' => 'Hình ảnh hiển thị',
                    'value' => (new GalleryImage())->countActive(),
                    'link' => '/admin/hinh-anh',
                ],
                [
                    'key' => 'new-admissions',
                    'label' => 'Đăng ký mới',
                    'value' => $admission->countByStatus('moi'),
                    'link' => '/admin/dang-ky',
                ],
                [
                    'key' => 'recent-admissions',
                    'label' => 'Đăng ký ' . self::RECENT_DAYS . ' ngày qua',
                    'value' => $admission->countSince(self::RECENT_DAYS),
                    'link' => '/admin/dang-ky',
                ],
            ],
            'latestAdmissions' => $admission->latest(self::LATEST_LIMIT),
            'recentNews' => $news->latest(self::LATEST_LIMIT),
            'admissionStatuses' => Admission::STATUSES,
            'newsStatuses' => News::STATUSES,
        ]);
    }
}
