<?php

declare(strict_types=1);

namespace App\Data\Products;

use App\Data\ImageData;
use App\Data\User\UserShortData;
use App\Models\Product;
use App\Support\Price;
use Spatie\LaravelData\Data;

class ProductData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public Price $price,
        public ImageData $preview_image,
        public ?UserShortData $user = null,
        public ?int $stock_items_count = null,
    ) {
    }
}
