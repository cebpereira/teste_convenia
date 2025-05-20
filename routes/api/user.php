<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('users')->group(function () {
    Route::post('/', [UserController::class, 'store']);
    Route::get('/me', [UserController::class, 'show'])->middleware('jwt');
    Route::put('/me', [UserController::class, 'update'])->middleware('jwt');
});
