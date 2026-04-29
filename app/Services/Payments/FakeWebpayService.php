<?php

declare(strict_types=1);

namespace App\Services\Payments;

use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class FakeWebpayService implements PaymentProvider
{
    public function generatePaymentUrl(float $amount, string $orderId, string $description): string
    {
        Payment::create([
            'user_id' => Auth::id(),
            'order_id' => $orderId,
            'amount' => $amount,
            'status' => 'pending',
        ]);

        return url("/test-payment-gateway/{$orderId}");
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function validateCallback(array $data): bool
    {

        return ($data['test_secret'] ?? null) === 'fake_secret_key';
    }

    public function getTransactionStatus(string $transactionId): string
    {
        return 'completed';
    }
}
