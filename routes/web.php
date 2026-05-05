<?php

use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::post('/language', [PublicController::class, 'language'])->name('language.update');
Route::get('/p/{staticPage:slug}', [PublicController::class, 'showStaticPage'])->name('static-page');

require __DIR__.'/cabinet.php';
require __DIR__.'/settings.php';
require __DIR__.'/guest.php';
