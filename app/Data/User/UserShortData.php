<?php

namespace App\Data\User;

use App\Models\User;
use Carbon\Carbon;
use Spatie\LaravelData\Data;

class UserShortData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public Carbon $registeredAt,
    ) {}

    public static function fromModel(User $user): self
    {
        return new self(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            registeredAt: $user->created_at,
        );
    }
}
