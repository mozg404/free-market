<?php

declare(strict_types=1);

namespace App\Data\Products;

use App\Attributes\FilepondImage;
use App\Models\Shop;
use App\Support\Filepond\Image;
use App\Support\Filepond\ImageStub;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class ProductData extends Data
{
    public string $imageUrl;

    public function __construct(
        public int $id,
        public string $name,
        public float $price,
        public float|null $priceDiscount = null,
        public bool $isAvailable,
        public Image|ImageStub $image,
        public Shop $shop,
    ){
        $this->imageUrl = $this->image->getUrl();
    }
}
