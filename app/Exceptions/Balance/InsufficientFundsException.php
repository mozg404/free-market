<?php

namespace App\Exceptions\Balance;

class InsufficientFundsException extends \RuntimeException
{
    protected $message = 'Недостаточно средств для выполнения операции';
    protected $code = 422;
}