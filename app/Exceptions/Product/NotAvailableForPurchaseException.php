<?php

namespace App\Exceptions\Product;

class NotAvailableForPurchaseException extends \Exception
{
    protected $message = 'Недоступно для покупки';
}