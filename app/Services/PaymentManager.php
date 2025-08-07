<?php

namespace App\Services;

use App\Enum\PaymentSource;
use App\Enum\TransactionType;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use App\Support\Sandbox;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentManager
{
    /**
     * Пополнить счет на $amount сумму
     * @param User $user
     * @param int $amount
     * @return Transaction
     * @throws \Throwable
     */
    public function topUpBalance(User $user, int $amount): Transaction
    {
        return $user->deposit($amount, TransactionType::REPLENISHMENT);
    }

    /**
     * Создает новый платеж
     * @param User $user
     * @param int $amount
     * @param PaymentSource $source
     * @param int|null $sourceId
     * @return Payment
     * @throws \JsonException
     */
    public function create(User $user, int $amount, PaymentSource $source, int|null $sourceId = null): Payment
    {
        // Создаем новый платеж
        $payment = Payment::new($user, $amount, $source, $sourceId);

        // Генерируем внешний ID
        $externalId = Sandbox::createId($payment->id, $amount);

        // Сохраняем внешний ID в модель
        $payment->toPend($externalId);

        return $payment;
    }

    /**
     * @throws \Throwable
     */
    public function changeBalanceFromExternalPayment(Payment $payment): void
    {
        DB::transaction(function () use ($payment) {
            // Пополняем баланс пользователя из платежа
            $payment->user->deposit(
                amount: $payment->amount,
                type: TransactionType::REPLENISHMENT,
                transactionable: $payment
            );

            $payment->toComplete();
        });
    }

    public function payOrder(Order $order)
    {
        DB::transaction(function () use ($order) {
            // Списываем со счета пользователя сумму заказа
            $order->user->deposit(
                amount: $order->amount,
                type: TransactionType::ORDER_PAYMENT,
                transactionable: $order
            );

            // Помечаем заказ как оплаченный
            $order->paid();
        });
    }
}