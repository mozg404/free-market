<?php

declare(strict_types=1);

namespace App\Data\Products;

use App\Data\ImageData;
use App\Data\User\UserShortData;
use App\Models\Product;
use App\Support\Price;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

class ProductData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public Price $price,
        public ImageData $preview_image,
        public ?UserShortData $user = null,
        public Carbon $created_at,
        public ?int $stock_items_count = null,
        public ?int $available_stock_items_count = null,
        public ?int $sold_stock_items_count = null,
        public ?int $reserved_stock_items_count = null,
    ) {
    }
}
