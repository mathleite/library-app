<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));

Route::prefix('authors')->group(function () {
    Route::get('/', fn () => view('authors/index'))->name('authors.index-view');
    Route::get('/create', fn () => view('authors/create'))->name('authors.create');
    Route::get('/{id}', fn () => view('authors/edit'))->name('authors.edit-view');
});

Route::prefix('subjects')->group(function () {
    Route::get('/', fn () => view('subjects/index'))->name('subjects.index-view');
    Route::get('/create', fn () => view('subjects/create'))->name('subjects.create');
    Route::get('/{id}', fn () => view('subjects/edit'))->name('subjects.edit-view');
});

Route::prefix('books')->group(function () {
    Route::get('/', fn () => view('books/index'))->name('books.index-view');
    Route::get('/create', fn () => view('books/create'))->name('books.create');
    Route::get('/{id}', fn () => view('books/edit'))->name('books.edit-view');
});
