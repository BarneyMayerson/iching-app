<?php

use App\Http\Controllers\Guest\GuestDivinationController;
use App\Http\Middleware\CheckGuestDivinationLimit;
use Illuminate\Support\Facades\Route;

Route::prefix('divination')->as('divination')->group(function (): void {
    Route::middleware(CheckGuestDivinationLimit::class)->group(function (): void {
        Route::get('create', [GuestDivinationController::class, 'create'])->name('.create');
        Route::post('store', [GuestDivinationController::class, 'store'])->name('.store');
    });

    Route::get('show', [GuestDivinationController::class, 'show'])->name('.show');
});
