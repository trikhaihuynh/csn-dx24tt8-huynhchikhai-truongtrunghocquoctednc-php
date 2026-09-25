<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->view('public/home', [
            'schoolName' => setting('ten_truong'),
            'slogan' => setting('slogan'),
            'introduction' => setting('gioi_thieu'),
        ]);
    }
}
