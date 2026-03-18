<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Src\Interfaces\Http\Controllers\AuthController;

Route::prefix('auth')->group(static function () {
    Route::post('register', [AuthController::class, 'register']);
});
