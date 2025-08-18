<?php

declare(strict_types=1);

namespace App\Data\Products;

use App\Data\User\UserShortData;
use App\Enum\ProductStatus;
use App\Support\Image;
use App\Support\Price;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

class ProductForListingData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public Price $price,
        public ?string $image_url,
        public ProductStatus $status,
        public ?UserShortData $user = null,
        public Carbon $created_at,
        public ?int $stock_items_count = null,
        public ?int $available_stock_items_count = null,
        public ?int $reserved_stock_items_count = null,
    ) {
    }
}
