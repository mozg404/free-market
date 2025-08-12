<?php

namespace App\Exceptions\Payment;

class UnknownPaymentException extends \ErrorException
{
    protected $message = 'Неизвестный платеж';
    protected $code = 404;
}