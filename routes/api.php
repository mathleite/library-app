<?php

use App\Infrastructure\Framework\Controllers\AuthorController;
use App\Infrastructure\Framework\Controllers\BookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::resource('authors', AuthorController::class);
    Route::resource('books', BookController::class);
});
