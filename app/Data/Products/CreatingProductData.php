<?php

declare(strict_types=1);

namespace App\Data\Products;

use App\Attributes\FilepondImage;
use App\Support\Filepond\Image;
use Illuminate\Http\Request;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class CreatingProductData extends Data
{
    public function __construct(
        public string       $name,
        public float        $price,
        public Image|string|null $previewImage = null,
        public bool         $isAvailable,
        public float|null   $priceDiscount = null,
        public string|null  $description = null,
        public array|null   $features = null,
    ){
        if (is_string($previewImage)) {
            $this->previewImage = Image::from($previewImage);
        }
    }

    public static function fromObject(Request $request): self
    {
        return new self('string', 10, null, true);
    }

//    public static function rules(ValidationContext $context): array
//    {
//        return [
//            'name' => 'required|string|min:3|max:255',
//            'price' => ['required', 'numeric'],
//            'previewImage' => ['required', new FilepondImage],
//            'isAvailable' => 'required|bool',
//        ];
//    }
}
