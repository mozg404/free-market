<?php

namespace App\Contracts;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;

interface PaymentGateway
{
    /**
     * Создает платеж для заказа
     */
    public function createForOrder(Order $order): Payment;

    /**
     * Создает платеж для пополнения баланса
     */
    public function createDeposit(User $user, int $amount): Payment;

    /**
     * Валидирует callback от платежного шлюза
     */
    public function validateCallback(array $data): Payment;

    /**
     * Возвращает URL для редиректа на оплату
     */
    public function getPaymentUrl(Payment $payment): string;
}