<?php

namespace App\Services\Payment;

use App\Contracts\PaymentGateway;
use App\Enum\PaymentSource;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Support\Sandbox;

class DemoPaymentGateway implements PaymentGateway
{
    public function createForOrder(Order $order): Payment
    {
        // Вычисляем недостающую сумму
        $amount = $order->amount - $order->user->balance;

        // Создаем новый платеж
        $payment = Payment::new($order->user, $amount, PaymentSource::ORDER, $order);

        // Генерируем внешней ID кассы
        $externalId = Sandbox::createId($payment->id, $amount);

        // Сохраняем внешний ID в модель
        $payment->toPend($externalId);

        return $payment;
    }

    public function createDeposit(User $user, int $amount): Payment
    {
        // Создаем новый платеж
        $payment = Payment::new($user, $amount, PaymentSource::REPLENISHMENT);

        // Генерируем внешний ID
        $externalId = Sandbox::createId($payment->id, $amount);

        // Сохраняем внешний ID в модель
        $payment->toPend($externalId);

        return $payment;
    }

    public function validateCallback(array $data): Payment
    {
        $payment = Payment::findOrFail($data['payment_id']);

        return $payment;
    }

    public function getPaymentUrl(Payment $payment): string
    {
        return Sandbox::generateUrlToPay($payment->external_id);
    }
}