<?php

use App\Http\Controllers\Cabinet\DivinationController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth', 'verified'])->prefix('cabinet')->as('cabinet')->group(function (): void {
    Route::get('', fn () => Inertia::render('Dashboard'));

    Route::prefix('divinations')->as('.divinations')->group(function (): void {
        Route::get('', [DivinationController::class, 'index'])->name('.index');
        Route::get('create', [DivinationController::class, 'create'])->name('.create');
        Route::post('store', [DivinationController::class, 'store'])->name('.store');
        Route::get('{reading}', [DivinationController::class, 'show'])->name('.show');
    });
});
