<?php

namespace App\Exceptions\Auth;

class InvalidPasswordException extends \DomainException
{
    protected $message = 'Неверный пароль';
}