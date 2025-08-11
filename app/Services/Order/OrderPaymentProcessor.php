<?php

namespace App\Services\Order;

use App\Enum\TransactionType;
use App\Exceptions\Billing\InsufficientFundsException;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderPaymentProcessor
{
    public function process(Order $order): void
    {
        if (!$order->user->hasEnoughBalance($order->amount)) {
            throw new InsufficientFundsException();
        }

        DB::transaction(function () use ($order) {
            // Списываем со счета пользователя сумму заказа
            $order->user->withdraw(
                amount: $order->amount,
                type: TransactionType::ORDER_PAYMENT,
                transactionable: $order
            );

            // Помечаем заказ как оплаченный
            $order->paid();
        });
    }
}