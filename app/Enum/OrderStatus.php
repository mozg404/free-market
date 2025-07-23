<?php

namespace App\Enum;

enum OrderStatus: string
{
    case NEW = 'new';
    case PAID = 'paid';
    case CANCELLED = 'cancelled';
}
