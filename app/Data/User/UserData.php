<?php

namespace App\Data\User;

use App\Models\User;
use Carbon\Carbon;
use Spatie\LaravelData\Data;

class UserData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public int $balance,
        public Carbon $created_at,
    ) {}
}
