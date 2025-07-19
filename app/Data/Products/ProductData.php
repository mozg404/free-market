<?php

declare(strict_types=1);

namespace App\Data\Products;

use App\Attributes\FilepondImage;
use App\Models\Shop;
use App\Support\Filepond\Image;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class ProductData extends Data
{
    public string $imageUrl;

    public function __construct(
        public Shop $shop,
        public string $name,
        public float $price,
        public Image|string $image,
        public bool $isAvailable,
        public float|null $priceDiscount = null
    ){
        $this->imageUrl = $this->image->getUrl();
    }
}
