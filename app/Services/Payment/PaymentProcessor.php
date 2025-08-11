<?php

namespace App\Services\Payment;

use App\Enum\TransactionType;
use App\Exceptions\Payment\PaymentAlreadyProcessedException;
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
     * @throws PaymentAlreadyProcessedException|\Throwable
     */
    public function process(Payment $payment)
    {
        if (!$payment->isPending()) {
            throw new PaymentAlreadyProcessedException();
        }

        // Пополняем баланс
        DB::transaction(function () use ($payment) {
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
            $payment->toComplete();
        });


    }
}