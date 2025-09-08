<?php

namespace App\Jobs;

use App\Data\Demo\DemoProductData;
use App\Services\Demo\DemoProductCreator;
use App\Services\User\UserQuery;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CreateSpecificDemoProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(
        public DemoProductData $data
    ) {
    }

    public function handle(UserQuery $userQuery, DemoProductCreator $productCreator): void
    {
        $user = $userQuery->query()
            ->withoutAdmin()
            ->inRandomOrder()
            ->first();

        $productCreator->create($user, $this->data);
    }
}
