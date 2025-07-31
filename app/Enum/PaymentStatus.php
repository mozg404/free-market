<?php

namespace App\Enum;

enum PaymentStatus: string
{
    case NEW = 'new';               // Статус при создании
    case PENDING = 'pending';       // В ожидании
    case COMPLETED = 'completed';   // Завершен
    case ERROR = 'error';           // Ошибка
    case CANCELLED = 'cancelled';   // Отменен
}
