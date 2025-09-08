<?php

namespace App\Services\Demo;

use App\Models\User;
use App\Services\User\UserAvatarChanger;
use App\Services\User\UserCreator;
use App\ValueObjects\Email;
use App\ValueObjects\Password;

readonly class DemoUserCreator
{
    public function __construct(
        private UserCreator $creator,
        private UserAvatarChanger $avatarChanger,
    ) {
    }

    public function createMainUser(): User
    {
        return $this->create(
            name: fake()->userName(),
            email: config('demo.main_user_email'),
            password: config('demo.main_user_password'),
            avatarPath: fake()->randomElement(include resource_path('data/user_avatars.php')),
            isAdmin: true
        );
    }

    public function createRandomUser(): User
    {
        return $this->create(
            name: fake()->userName(),
            email: fake()->unique()->email(),
            password: config('demo.random_user_password'),
            avatarPath: fake()->randomElement(include resource_path('data/user_avatars.php')),
        );
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