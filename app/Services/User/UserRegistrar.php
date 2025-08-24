<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Events\UserRegistered;
use App\Exceptions\User\UserAlreadyRegisteredException;
use App\Models\User;
use App\ValueObjects\Email;
use App\ValueObjects\Password;
use Webmozart\Assert\Assert;

class UserRegistrar
{
    public function register(string $name, Email $email, Password $password): User
    {
        Assert::stringNotEmpty($name);
        Assert::minLength($name, 3);
        Assert::maxLength($name, 255);

        if (User::query()->checkExistsByEmail($email->value)) {
            throw new UserAlreadyRegisteredException();
        }

        $user = new User();
        $user->name = $name;
        $user->email = $email->value;
        $user->password = $password->hashed();
        $user->save();

        event(new UserRegistered($user));

        return $user;
    }
}
