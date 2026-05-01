<?php

use App\Http\Controllers\Cabinet\DashboardController;
use App\Http\Controllers\Cabinet\DivinationController;
use App\Http\Controllers\Cabinet\DonateController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('cabinet')->as('cabinet')->group(function (): void {
    Route::get('', DashboardController::class)->name('.dashboard');

    Route::prefix('divinations')->as('.divinations')->group(function (): void {
        Route::get('', [DivinationController::class, 'index'])->name('.index');
        Route::get('create', [DivinationController::class, 'create'])->name('.create');
        Route::post('store', [DivinationController::class, 'store'])->name('.store');
        Route::get('{reading}/export', [DivinationController::class, 'export'])->name('.export');
        Route::get('{reading}', [DivinationController::class, 'show'])->name('.show');
        Route::post('/{reading}/interpret', [DivinationController::class, 'interpret'])
            ->middleware('throttle:3,1')
            ->name('.interpret');
        Route::patch('/{reading}/cancel-interpretation', [DivinationController::class, 'cancelInterpretation'])->name('.cancel-interpretation');
        Route::delete('{reading}', [DivinationController::class, 'destroy'])->name('.delete');
    });

    Route::prefix('donate')->as('.donate')->group(function (): void {
        Route::get('instructions', [DonateController::class, 'instructions'])->name('.instructions');
    });
});
