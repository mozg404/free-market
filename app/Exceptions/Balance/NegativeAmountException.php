<?php

namespace App\Exceptions\Balance;

class NegativeAmountException extends \DomainException
{
    protected $message = 'Сумма не может быть отрицательной';
    protected $code = 422;
}