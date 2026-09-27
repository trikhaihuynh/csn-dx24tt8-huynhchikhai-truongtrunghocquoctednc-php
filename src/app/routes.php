<?php

declare(strict_types=1);

use App\Controllers\Admin;
use App\Controllers\AdmissionController;
use App\Controllers\GalleryController;
use App\Controllers\HomeController;
use App\Controllers\NewsController;
use App\Controllers\ProgramController;

$router->get('/', [HomeController::class, 'index']);
$router->get('/gioi-thieu', [HomeController::class, 'about']);
$router->get('/lien-he', [HomeController::class, 'contact']);
$router->get('/chuong-trinh', [ProgramController::class, 'index']);
$router->get('/chuong-trinh/{slug}', [ProgramController::class, 'show']);
$router->get('/tin-tuc', [NewsController::class, 'index']);
$router->get('/tin-tuc/{slug}', [NewsController::class, 'show']);
$router->get('/hinh-anh', [GalleryController::class, 'index']);
$router->get('/dang-ky-nhap-hoc', [AdmissionController::class, 'create']);
$router->post('/dang-ky-nhap-hoc', [AdmissionController::class, 'store']);
$router->get('/dang-ky-nhap-hoc/thanh-cong', [AdmissionController::class, 'success']);

$router->get('/admin/login', [Admin\AuthController::class, 'showLogin']);
$router->post('/admin/login', [Admin\AuthController::class, 'login']);
$router->post('/admin/logout', [Admin\AuthController::class, 'logout']);
$router->get('/admin', [Admin\DashboardController::class, 'index']);

$router->get('/admin/tin-tuc', [Admin\NewsController::class, 'index']);
$router->get('/admin/tin-tuc/them', [Admin\NewsController::class, 'create']);
$router->post('/admin/tin-tuc/them', [Admin\NewsController::class, 'store']);
$router->get('/admin/tin-tuc/{id}/sua', [Admin\NewsController::class, 'edit']);
$router->post('/admin/tin-tuc/{id}/sua', [Admin\NewsController::class, 'update']);
$router->post('/admin/tin-tuc/{id}/xoa', [Admin\NewsController::class, 'destroy']);

$router->get('/admin/chuong-trinh', [Admin\ProgramController::class, 'index']);
$router->get('/admin/chuong-trinh/them', [Admin\ProgramController::class, 'create']);
$router->post('/admin/chuong-trinh/them', [Admin\ProgramController::class, 'store']);
$router->get('/admin/chuong-trinh/{id}/sua', [Admin\ProgramController::class, 'edit']);
$router->post('/admin/chuong-trinh/{id}/sua', [Admin\ProgramController::class, 'update']);
$router->post('/admin/chuong-trinh/{id}/xoa', [Admin\ProgramController::class, 'destroy']);

$router->get('/admin/hinh-anh', [Admin\GalleryController::class, 'index']);
$router->get('/admin/hinh-anh/them', [Admin\GalleryController::class, 'create']);
$router->post('/admin/hinh-anh/them', [Admin\GalleryController::class, 'store']);
$router->get('/admin/hinh-anh/{id}/sua', [Admin\GalleryController::class, 'edit']);
$router->post('/admin/hinh-anh/{id}/sua', [Admin\GalleryController::class, 'update']);
$router->post('/admin/hinh-anh/{id}/xoa', [Admin\GalleryController::class, 'destroy']);

$router->get('/admin/dang-ky', [Admin\AdmissionController::class, 'index']);
$router->get('/admin/dang-ky/{id}', [Admin\AdmissionController::class, 'show']);
$router->post('/admin/dang-ky/{id}', [Admin\AdmissionController::class, 'update']);
$router->post('/admin/dang-ky/{id}/xoa', [Admin\AdmissionController::class, 'destroy']);

$router->get('/admin/cai-dat', [Admin\SettingController::class, 'edit']);
$router->post('/admin/cai-dat', [Admin\SettingController::class, 'update']);

$router->get('/admin/tai-khoan', [Admin\UserController::class, 'index']);
$router->get('/admin/tai-khoan/them', [Admin\UserController::class, 'create']);
$router->post('/admin/tai-khoan/them', [Admin\UserController::class, 'store']);
$router->get('/admin/tai-khoan/{id}/sua', [Admin\UserController::class, 'edit']);
$router->post('/admin/tai-khoan/{id}/sua', [Admin\UserController::class, 'update']);
$router->post('/admin/tai-khoan/{id}/xoa', [Admin\UserController::class, 'destroy']);
