<?php

namespace App\Jobs;

use App\Services\User\DemoUserCreator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CreateRandomDemoUser implements ShouldQueue
{
    use Queueable;

    public function handle(DemoUserCreator $creator): void
    {
        $creator->create(
            name: fake()->userName(),
            email: fake()->unique()->email(),
            password: config('demo.random_user_password'),
            avatarPath: fake()->randomElement(include resource_path('data/user_avatars.php')),
        );
    }
}
