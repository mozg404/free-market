<?php

declare(strict_types=1);

namespace App\Data\Products;

use App\Attributes\FilepondImage;
use App\Support\Filepond\Image;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class UpdatingProductData extends Data
{
    public function __construct(
        public string       $name,
        public float        $price,
        public Image|string|null $previewImage,
        public bool         $isAvailable,
        public float|null   $priceDiscount = null,
        public string|null  $description,
    ){
        if (is_string($previewImage)) {
            $this->previewImage = Image::from($previewImage);
        }
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'price' => ['required', 'numeric'],
            'previewImage' => ['required', new FilepondImage],
            'isAvailable' => 'required|bool',
        ];
    }
}
