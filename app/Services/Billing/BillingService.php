<?php

namespace App\Services\Billing;

use App\Enum\PaymentSource;
use App\Enum\TransactionType;
use App\Exceptions\Billing\InsufficientFundsException;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Support\Sandbox;
use Illuminate\Support\Facades\DB;

class BillingService
{
    /**
     * Создает внешний платеж на пополнение баланса
     * @param User $user
     * @param int $amount
     * @return ExternalPayment
     * @throws \JsonException
     */
    public function createExternalReplenishmentPayment(User $user, int $amount): ExternalPayment
    {
        // Создаем новый платеж
        $payment = Payment::new($user, $amount, PaymentSource::REPLENISHMENT);

        // Генерируем внешний ID
        $externalId = Sandbox::createId($payment->id, $amount);

        // Сохраняем внешний ID в модель
        $payment->toPend($externalId);

        return new ExternalPayment(
            payment: $payment,
            toPayUrl: Sandbox::generateUrlToPay($externalId)
        );
    }

    /**
     * Создает внешний платеж для оплаты заказа
     * @param Order $order
     * @return ExternalPayment
     * @throws \JsonException
     */
    public function createExternalOrderPayment(Order $order): ExternalPayment
    {
        // Вычисляем недостающую сумму
        $amount = $order->amount - $order->user->balance;

        // Создаем новый платеж
        $payment = Payment::new($order->user, $amount, PaymentSource::ORDER, $order->id);

        // Генерируем внешней ID кассы
        $externalId = Sandbox::createId($payment->id, $amount);

        // Сохраняем внешний ID в модель
        $payment->toPend($externalId);

        return new ExternalPayment(
            payment: $payment,
            toPayUrl: Sandbox::generateUrlToPay($externalId)
        );
    }
    
    /**
     * Пополняет баланс из внешнего платежа
     * @throws \Throwable
     */
    public function processPayment(Payment $payment): void
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

    /**
     * Проверят достаточно ли средств на балансе для оплаты заказа
     * @param Order $order
     * @return bool
     */
    public function hasEnoughBalanceFor(Order $order): bool
    {
        return $order->user->hasEnoughBalance($order->amount);
    }

    /**
     * Оплачивает заказ из баланса пользователя
     * @param Order $order
     * @return void
     * @throws \Throwable
     */
    public function processOrderPayment(Order $order): void
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