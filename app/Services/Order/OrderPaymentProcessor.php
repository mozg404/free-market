<?php

namespace App\Services\Order;

use App\Enum\TransactionType;
use App\Exceptions\Billing\InsufficientFundsException;
use App\Models\Order;
use App\Models\OrderItem;
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
                type: TransactionType::PURCHASE,
                transactionable: $order
            );

            // Меняем статус позиций на складе и пополняем баланс продавца
            $order->items()
                ->withStockItem()
                ->withProductUser()
                ->get()->each(function (OrderItem $item) use ($order) {
                    $item->stockItem->markAsSold($order->user);
                    $item->stockItem->product->user->deposit(
                        amount: $item->price->getCurrentPrice(),
                        type: TransactionType::SALE,
                        transactionable: $item->stockItem
                    );
                });

            // Меняем статус заказа
            $order->markAsPaid();
        });
    }
}