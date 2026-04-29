<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\Payments\PaymentProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    public function __construct(private readonly PaymentProvider $paymentProvider) {}

    public function handleWebpay(Request $request): JsonResponse
    {
        $data = $request->all();

        // 1. Логируем входящий запрос
        Log::channel('payments')->info('Webpay Callback Received', $data);

        // 2. Валидируем подпись (Security Check) через наш сервис
        if (! $this->paymentProvider->validateCallback($data)) {
            Log::channel('payments')->warning('Invalid Payment Signature', $data);

            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // 3. Ищем платеж в нашей базе по order_id
        $orderId = $data['site_order_id'] ?? $data['order_id'] ?? null;

        /** @var Payment $payment */
        $payment = Payment::query()->where('order_id', $orderId)->first();

        if (! $payment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }

        // 4. Если платеж уже обработан, просто отвечаем OK
        if ($payment->status !== 'pending') {
            return response()->json(['status' => 'already_processed']);
        }

        // 5. Обновляем статус на основе данных от системы
        // В реальном Webpay это будет проверка поля типа 'payment_status'
        if ($data['status'] === 'success') {
            $payment->update([
                'status' => 'completed',
                'external_id' => $data['transaction_id'] ?? null,
                'payload' => $data,
            ]);

            // ЗДЕСЬ: Логика активации тарифа для пользователя
            // $payment->user->activatePlan($payment->plan_id);

        } else {
            $payment->update([
                'status' => 'failed',
                'payload' => $data,
            ]);
        }

        return response()->json(['status' => 'accepted']);
    }
}
