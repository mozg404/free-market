<?php

namespace App\Exceptions\Order;

class OrderAccessDeniedException extends \DomainException
{
    protected $message = 'Заказ не принадлежит пользователю';
    protected $code = 403;
}