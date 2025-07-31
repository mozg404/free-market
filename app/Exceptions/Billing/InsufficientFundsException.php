<?php

namespace App\Exceptions\Billing;

class InsufficientFundsException extends \RuntimeException
{
    protected $message = 'Недостаточно средств для выполнения операции';
    protected $code = 422;
}