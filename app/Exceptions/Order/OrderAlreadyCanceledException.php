<?php

namespace App\Exceptions\Order;

class OrderAlreadyCanceledException extends \DomainException
{
    protected $message = 'Заказ уже отменён';
    protected $code = 409;
}