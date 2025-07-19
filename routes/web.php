<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PosterController;

// Halaman utama (frontend)
Route::get('/', [PosterController::class, 'homeIndex'])->name('home');

// Halaman download
Route::get('/download/{id}', [PosterController::class, 'downloadPage'])->name('download.page');
Route::get('/download/image/{id}', [PosterController::class, 'downloadImage'])->name('download.image');

// Regenerasi poster
Route::post('/posters/{id}/regenerate', [PosterController::class, 'regenerate'])->name('posters.regenerate');

// Admin routes
Route::get('/admin', [PosterController::class, 'index'])->name('admin');
Route::delete('/posters/destroy-all', [PosterController::class, 'destroyAll'])->name('posters.destroyAll');
Route::resource('posters', PosterController::class);
