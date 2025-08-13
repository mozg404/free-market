<?php

namespace App\Exceptions\Balance;

class ZeroAmountException extends \DomainException
{
    protected $message = 'Сумма не может быть нулевой';
    protected $code = 422;
}