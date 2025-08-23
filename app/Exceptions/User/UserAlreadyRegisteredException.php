<?php

declare(strict_types=1);

namespace App\Exceptions\User;

use DomainException;

class UserAlreadyRegisteredException extends DomainException
{
    protected $message = 'Пользователь уже зарегистрирован';
    protected $code = 409;
}
