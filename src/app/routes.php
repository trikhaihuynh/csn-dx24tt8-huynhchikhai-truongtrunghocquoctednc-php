<?php

declare(strict_types=1);

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
