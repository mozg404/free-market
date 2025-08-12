<?php

namespace App\Enum;

enum TransactionType: string
{
    case DEPOSIT = 'deposit'; // Пополнение
    case PURCHASE = 'purchase'; // Оплата заказа
    case SALE = 'sale'; // Продажа товаров
}