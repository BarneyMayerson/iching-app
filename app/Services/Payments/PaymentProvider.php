<?php

declare(strict_types=1);

namespace App\Services\Payments;

interface PaymentProvider
{
    /**
     * Create form/link to payment
     */
    public function generatePaymentUrl(float $amount, string $orderId, string $description): string;

    /**
     * Validates the notification from the payment system (Callback/Webhook)
     *
     * @param  array<string, mixed>  $data
     */
    public function validateCallback(array $data): bool;

    /**
     * Get payment status
     */
    public function getTransactionStatus(string $transactionId): string;
}
