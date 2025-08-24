<?php

namespace App\Exceptions\Auth\EmailVerification;

class EmailAlreadyVerifiedException extends \DomainException
{
    protected $message = 'Email уже подтверждён';
    protected $code = 409;
}