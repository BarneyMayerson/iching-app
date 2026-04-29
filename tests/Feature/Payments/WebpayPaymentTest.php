<?php

use App\Models\Payment;
use App\Models\User;
use App\Services\Payments\FakeWebpayService;
use App\Services\Payments\PaymentProvider;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

it('creates a pending payment record in database', function () {
    /** @var User $user */
    $user = User::factory()->create();
    actingAs($user);

    $service = new FakeWebpayService;
    $service->generatePaymentUrl(25.0, 'ORD-555', 'Standard');

    assertDatabaseHas('payments', [
        'order_id' => 'ORD-555',
        'amount' => 25.0,
        'status' => 'pending',
        'user_id' => $user->id,
    ]);
});

it('successfully processes a successful callback', function () {
    app()->bind(PaymentProvider::class, FakeWebpayService::class);

    /** @var User $user */
    $user = User::factory()->create();

    $payment = Payment::create([
        'order_id' => 'TEST-123',
        'amount' => 25.0,
        'status' => 'pending',
        'user_id' => $user->id,
    ]);

    $response = postJson('/api/payments/callback/webpay', [
        'order_id' => 'TEST-123',
        'status' => 'success',
        'transaction_id' => 'TX-999',
        'test_secret' => 'fake_secret_key', // То, что ожидает FakeService
    ]);

    $response->assertOk();

    expect($payment->fresh()->status)->toBe('completed');
    expect($payment->fresh()->external_id)->toBe('TX-999');
});
