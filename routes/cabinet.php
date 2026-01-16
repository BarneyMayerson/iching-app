<?php

use App\Http\Controllers\Cabinet\DivinationController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth', 'verified'])->prefix('dashboard')->as('dashboard')->group(function (): void {
    Route::get('', fn () => Inertia::render('Dashboard'));

    Route::prefix('divinations')->as('.divinations')->group(function (): void {
        Route::get('', [DivinationController::class, 'index'])->name('.index');
        Route::post('', [DivinationController::class, 'store'])->name('.store');
    });
});
