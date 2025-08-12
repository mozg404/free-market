<?php

namespace App\Exceptions\Payment;

class PaymentFailedException extends \Exception
{
    protected $message = 'Платеж был завершен с ошибкой';
}