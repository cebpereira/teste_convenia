<?php

use Illuminate\Support\Facades\Route;

Route::get('/hello', function () {
    return response()->json(['message' => 'Hello world!']);
});

Route::group([], function () {
    require __DIR__.'/api/auth.php';
    require __DIR__.'/api/user.php';
    require __DIR__.'/api/collaborator.php';
});
