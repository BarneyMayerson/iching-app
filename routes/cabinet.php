<?php

use App\Http\Controllers\Cabinet\DashboardController;
use App\Http\Controllers\Cabinet\DivinationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('cabinet')->as('cabinet')->group(function (): void {
    Route::get('', DashboardController::class)->name('.dashboard');

    Route::prefix('divinations')->as('.divinations')->group(function (): void {
        Route::get('', [DivinationController::class, 'index'])->name('.index');
        Route::get('create', [DivinationController::class, 'create'])->name('.create');
        Route::post('store', [DivinationController::class, 'store'])->name('.store');
        Route::get('{reading}/export', [DivinationController::class, 'export'])->name('.export');
        Route::get('{reading}', [DivinationController::class, 'show'])->name('.show');
        Route::delete('{reading}', [DivinationController::class, 'destroy'])->name('.delete');
    });
});
