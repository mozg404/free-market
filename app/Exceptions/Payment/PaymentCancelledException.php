<?php

namespace App\Exceptions\Payment;

class PaymentCancelledException extends \ErrorException
{
    protected $message = 'Платеж был отменен';
}