<?php

namespace App\Exceptions\Product;

class NotEnoughStockException extends \DomainException
{
    protected $message = 'Недостаточно товара на складе';
    protected $code = 422;
}