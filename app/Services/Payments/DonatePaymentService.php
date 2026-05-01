<?php

declare(strict_types=1);

namespace App\Services\Payments;

class DonatePaymentService implements PaymentProvider
{
    public function generatePaymentUrl(float $amount, string $orderId, string $description): string
    {
        return route('cabinet.donate.instructions', ['order' => $orderId]);
    }

    public function validateCallback(array $data): bool
    {
        return false;
    }

    public function getTransactionStatus(string $transactionId): string
    {
        return 'pending';
    }
}
