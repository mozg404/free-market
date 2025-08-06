<?php

namespace App\Exceptions\Cart;

class NotEnoughStockException extends \DomainException
{
    protected $message = 'Недостаточно товара на складе';
    protected $code = 422;
}