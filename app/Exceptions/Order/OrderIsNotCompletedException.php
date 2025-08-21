<?php

namespace App\Exceptions\Order;

class OrderIsNotCompletedException extends \DomainException
{
    protected $message = 'Заказ не завершен';
    protected $code = 409;
}