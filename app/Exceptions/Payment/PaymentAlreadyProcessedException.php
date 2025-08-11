<?php

namespace App\Exceptions\Payment;

class PaymentAlreadyProcessedException extends \ErrorException
{
    protected $message = 'Платеж уже обработан';
    protected $code = 422;
}