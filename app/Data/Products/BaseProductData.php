<?php

declare(strict_types=1);

namespace App\Data\Products;

use App\Attributes\FilepondImage;
use App\Models\Feature;
use App\Models\Product;
use App\Support\Filepond\Image;
use Illuminate\Http\Request;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class BaseProductData extends Data
{
    public function __construct(
        public string      $name,
        public int         $priceBase,
        public int|null    $priceDiscount = null,
        public int|null    $categoryId = null,
        public string|null $previewImage = null,
        public string|null $description = null,
        public array|null  $features = null,
    )
    {}

    public static function fromModel(Product $product): static
    {
        $features = [];

        foreach ($product->features as $feature) {
            $features[$feature->id] = $feature->pivot->value;
        }

        return new static(
            name: $product->name,
            priceBase: $product->price_base,
            priceDiscount: $product->price_discount,
            categoryId: $product->category_id,
            previewImage: $product->preview_image?->id,
            description: $product->description,
            features: $features,
        );
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'priceBase' => ['required', 'numeric'],
            'priceDiscount' => ['sometimes', 'nullable', 'numeric'],
            'categoryId' => ['sometimes', 'int', 'exists:categories,id'],
            'previewImage' => ['sometimes', 'nullable', new FilepondImage],
            'features' => ['sometimes', 'array'],
        ];
    }
}
