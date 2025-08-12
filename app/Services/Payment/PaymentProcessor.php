<?php

namespace App\Services\Payment;

use App\Enum\TransactionType;
use App\Exceptions\Payment\PaymentAlreadyCompletedException;
use App\Exceptions\Payment\PaymentFailedException;
use App\Exceptions\Payment\PaymentCancelledException;
use App\Models\Payment;
use App\Services\Order\OrderPaymentProcessor;
use Illuminate\Support\Facades\DB;

class PaymentProcessor
{
    public function __construct(
        private readonly OrderPaymentProcessor $orderProcessor,
    )
    {}

    /**
     * @throws PaymentAlreadyCompletedException|\Throwable
     */
    public function process(Payment $payment): Payment
    {
        throw_if($payment->isCompleted(), new PaymentAlreadyCompletedException());
        throw_if($payment->isCancelled(), new PaymentCancelledException());
        throw_if($payment->isFailed(), new PaymentFailedException());

        return DB::transaction(function () use ($payment) {
            // Пополняем баланс пользователя из платежа
            $payment->user->deposit(
                amount: $payment->amount,
                type: TransactionType::REPLENISHMENT,
                transactionable: $payment
            );

            // Если оплата в процессе заказа - выполняем заказ
            if ($payment->isOrderSource()) {
                $this->orderProcessor->process($payment->sourceable);
            }

            // Помечаем, как выполненный
            return $payment->markAsCompleted();
        });
    }
}