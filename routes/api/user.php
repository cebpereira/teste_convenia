<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('users')->middleware('jwt')->group(function () {
    Route::post('/', [UserController::class, 'store']);
    Route::get('/me', [UserController::class, 'show']);
    Route::put('/me', [UserController::class, 'update']);
});
