<?php

use App\Http\Controllers\PaymentCallbackController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', fn(Request $request) => $request->user())->middleware('auth:sanctum');

Route::post('/payments/callback/webpay', [PaymentCallbackController::class, 'handleWebpay'])->name('payments.callback.webpay');
