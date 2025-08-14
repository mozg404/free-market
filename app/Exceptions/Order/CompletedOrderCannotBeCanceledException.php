<?php

namespace App\Exceptions\Order;

class CompletedOrderCannotBeCanceledException extends \DomainException
{
    protected $message = 'Выполненный заказ нельзя отменить';
    protected $code = 409;
}