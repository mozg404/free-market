<?php

namespace App\Enum;

enum OrderStatus: string
{
    /** Новый */
    case NEW = 'new';
    /** Оплачено */
    case PAID = 'paid';
    /** Отменен */
    case CANCELLED = 'cancelled';
}
