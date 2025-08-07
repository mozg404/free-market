<?php

namespace App\Enum;

enum TransactionType: string
{
    case REPLENISHMENT = 'replenishment'; // Пополнение
    case ORDER_PAYMENT = 'order_payment'; // Оплата заказа
}