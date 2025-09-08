<?php

namespace App\Jobs;

use App\Services\Demo\DemoOrderCreator;
use App\Services\User\UserQuery;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class CreateRandomDemoOrder implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function handle(UserQuery $userQuery, DemoOrderCreator $creator): void
    {
        $user = $userQuery->query()
            ->withoutAdmin()
            ->inRandomOrder()
            ->first();

        $creator->createAndComplete($user);
    }
}
