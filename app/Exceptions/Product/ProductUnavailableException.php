<?php

namespace App\Exceptions\Product;

class ProductUnavailableException extends \DomainException
{
    protected $message = 'Недоступно для покупки';
    protected $code = 400;
}