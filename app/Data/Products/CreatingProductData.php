<?php

declare(strict_types=1);

namespace App\Data\Products;

use App\Attributes\FilepondImage;
use App\Support\Filepond\Image;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class CreatingProductData extends Data
{
    public function __construct(
        public int $shopId,
        public string $name,
        public float $price,
        public Image|string $image,
        public bool $isAvailable,
        public float|null $priceDiscount = null
    ){
        if (is_string($image)) {
            $this->image = new Image($image);
        }
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            'shopId' => 'required|int|exists:App\Models\Shop,id',
            'name' => 'required|string|min:3|max:255',
            'image' => ['required', new FilepondImage],
        ];
    }
}
