<?php

namespace App\Data;

use App\Models\User;
use Carbon\Carbon;
use Spatie\LaravelData\Data;

class UserData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public int $balance,
        public Carbon $registeredAt,
    ) {}

    public static function fromModel(User $user): self
    {
        return new self(
            id: $user->id,
            name: $user->name,
            balance: $user->balance,
            registeredAt: $user->created_at,
        );
    }
}
