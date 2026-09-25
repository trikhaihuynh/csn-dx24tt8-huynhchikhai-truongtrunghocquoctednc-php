<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\GalleryImage;
use App\Models\News;
use App\Models\Program;

class HomeController extends Controller
{
    private const HOME_NEWS_LIMIT = 3;
    private const HOME_GALLERY_LIMIT = 4;

    public function index(): void
    {
        $this->view('public/home', [
            'schoolName' => setting('ten_truong', APP_NAME),
            'slogan' => setting('slogan'),
            'introduction' => setting('gioi_thieu'),
            'programs' => (new Program())->allActive(),
            'latestNews' => (new News())->latestPublished(self::HOME_NEWS_LIMIT),
            'galleryImages' => (new GalleryImage())->latestActive(self::HOME_GALLERY_LIMIT),
        ]);
    }

    public function about(): void
    {
        $this->view('public/about', [
            'title' => 'Giới thiệu',
            'schoolName' => setting('ten_truong', APP_NAME),
            'schoolNameEnglish' => setting('ten_truong_en'),
            'slogan' => setting('slogan'),
            'introduction' => setting('gioi_thieu'),
            'programs' => (new Program())->allActive(),
        ]);
    }

    public function contact(): void
    {
        $this->view('public/contact', [
            'title' => 'Liên hệ',
            'schoolName' => setting('ten_truong', APP_NAME),
            'address' => setting('dia_chi'),
            'phoneNumber' => setting('dien_thoai'),
            'emailAddress' => setting('email'),
            'socialLinks' => array_filter([
                'Facebook' => setting('facebook'),
                'YouTube' => setting('youtube'),
            ]),
        ]);
    }
}
