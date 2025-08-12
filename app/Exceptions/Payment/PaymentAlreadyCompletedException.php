<?php

namespace App\Exceptions\Payment;

class PaymentAlreadyCompletedException extends \ErrorException
{
    protected $message = 'Платеж уже обработан';
    protected $code = 422;
}