<?php

declare(strict_types=1);

use App\Controllers\Admin;
use App\Controllers\AdmissionController;
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
