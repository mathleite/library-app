<?php

use App\Infrastructure\Framework\Controllers\AuthorController;
use App\Infrastructure\Framework\Controllers\BookController;
use App\Infrastructure\Framework\Controllers\SubjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::prefix('authors')->group(function () {
        Route::get('/', [AuthorController::class, 'index'])->name('authors.api.index');
        Route::post('/', [AuthorController::class, 'store'])->name('authors.api.store');
        Route::get('/{id}', [AuthorController::class, 'show'])->name('authors.api.show');
        Route::put('/{id}', [AuthorController::class, 'update'])->name('authors.api.update');
        Route::delete('/{id}', [AuthorController::class, 'destroy'])->name('authors.api.destroy');
    });
    Route::prefix('subjects')->group(function () {
        Route::get('/', [SubjectController::class, 'index'])->name('subjects.api.index');
        Route::post('/', [SubjectController::class, 'store'])->name('subjects.api.store');
        Route::get('/{id}', [SubjectController::class, 'show'])->name('subjects.api.show');
        Route::put('/{id}', [SubjectController::class, 'update'])->name('subjects.api.update');
        Route::delete('/{id}', [SubjectController::class, 'destroy'])->name('subjects.api.destroy');
    });
    Route::prefix('books')->group(function () {
        Route::get('/', [BookController::class, 'index'])->name('books.api.index');
        Route::post('/', [BookController::class, 'store'])->name('books.api.store');
        Route::get('/{id}', [BookController::class, 'show'])->name('books.api.show');
        Route::put('/{id}', [BookController::class, 'update'])->name('books.api.update');
        Route::delete('/{id}', [BookController::class, 'destroy'])->name('books.api.destroy');
    });
});
