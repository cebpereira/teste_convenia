<?php

use App\Http\Controllers\Api\CollaboratorController;
use Illuminate\Support\Facades\Route;

Route::prefix('collaborators')->middleware('jwt')->group(function () {
    Route::post('/', [CollaboratorController::class, 'store']);
    Route::post('/import', [CollaboratorController::class, 'import']);
    Route::get('/', [CollaboratorController::class, 'list']);
    Route::put('/{collaborator}', [CollaboratorController::class, 'update']);
    Route::delete('/{collaborator}', [CollaboratorController::class, 'destroy']);
});
