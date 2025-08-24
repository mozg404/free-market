<?php

namespace App\Exceptions\Auth\EmailVerification;

class EmailNotVerifiedException extends \DomainException
{
    protected $message = 'Email не подтверждён';
    protected $code = 403;
}