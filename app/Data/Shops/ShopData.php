<?php

namespace App\Data\Shops;

use Spatie\LaravelData\Data;

class ShopData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
    ) {}
}
