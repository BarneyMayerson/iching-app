<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', fn () => Inertia::render('Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
    'canLogin' => Route::has('login'),
]))->name('home');

Route::post('/language', function (Request $request) {
    $request->session()->put('locale', $request->language);

    return back();
})->name('language.update');

require __DIR__.'/cabinet.php';
require __DIR__.'/settings.php';
