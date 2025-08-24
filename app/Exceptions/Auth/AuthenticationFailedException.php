<?php

namespace App\Exceptions\Auth;

use Illuminate\Auth\AuthenticationException;

class AuthenticationFailedException extends \DomainException
{
    protected $message = 'Неверный логин или пароль';
    protected $code = 401;
}