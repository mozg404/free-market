<?php

namespace App\Data\User;

use App\Models\User;
use Carbon\Carbon;
use Spatie\LaravelData\Data;

class UserForListingData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public ?string $avatar,
        public Carbon $created_at,
        public ?int $available_products_count,
        public ?int $sold_stock_count,
    ) {}
}
