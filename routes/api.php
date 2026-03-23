<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Src\Interfaces\Http\Controllers\AuthController;
use Src\Interfaces\Http\Controllers\ProfileController;

Route::prefix('auth')->group(static function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

Route::middleware(\Src\Interfaces\Http\Middleware\JwtAuthMiddleware::class)->group(function () {
    Route::get('/profile', [ProfileController::class, 'me']);
});
