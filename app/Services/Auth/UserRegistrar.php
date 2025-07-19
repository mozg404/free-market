<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Data\UserRegisteringData;
use App\Exceptions\Auth\UserAlreadyRegisteredException;
use App\Models\User;

class UserRegistrar
{
    public function register(UserRegisteringData $data): User
    {
        // Проверка на существование
        if (User::query()->where('email', $data->email)->exists()) {
            throw new UserAlreadyRegisteredException('User with email '.$data->email.' is already registered');
        }

        // Регистрация
        $user = new User();
        $user->name = $data->name;
        $user->email = $data->email;
        $user->password = $data->password;
        $user->save();

        return $user;
    }
}
