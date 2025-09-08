<?php

namespace App\Services\User;

use App\Models\User;
use App\ValueObjects\Email;
use App\ValueObjects\Password;

readonly class DemoUserCreator
{
    public function __construct(
        private UserCreator $creator,
        private UserAvatarChanger $avatarChanger,
    ) {
    }

    public function create(string $name, string $email, string $password, string $avatarPath, bool $isAdmin = false): User
    {
        $user = $this->creator->create(
            name: $name,
            email: new Email($email),
            password: new Password($password),
            emailVerified: true,
            isAdmin: $isAdmin
        );
        $this->avatarChanger->changeFromPath($user, $avatarPath);

        return $user;
    }
}