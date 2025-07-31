<?php

namespace App\Enum;

enum TransactionType: string
{
    case INFLOW  = 'inflow';   // Пополнение (входящий платёж)
    case OUTFLOW = 'outflow';  // Списание (оплата заказа)
}