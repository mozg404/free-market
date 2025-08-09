<?php

declare(strict_types=1);

namespace App\Data\Products;

use App\Models\Product;
use App\Support\Image;
use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class ProductEditableData extends Data
{
    public function __construct(
        public string $name,
        public int $price_base,
        public int|null $price_discount = null,
        public int|null $category_id = null,
        public string|UploadedFile|null $preview_image = null,
        public string|null $description = null,
        public array|null $features = null,
    ) {
        if ($this->preview_image instanceof UploadedFile) {
            $this->preview_image = Image::createFromUploadedFile($this->preview_image)->getUrl();
        }
    }

    public static function fromModel(Product $product): static
    {
        $features = [];

        foreach ($product->features as $feature) {
            $features[$feature->id] = $feature->pivot->value;
        }

        return new static(
            name: $product->name,
            price_base: $product->price->getBasePrice(),
            price_discount: $product->price->getDiscountPrice(),
            category_id: $product->category_id,
            preview_image: $product->preview_image->getUrl(),
            description: $product->description,
            features: $features,
        );
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'price_base' => ['required', 'numeric'],
            'price_discount' => ['sometimes', 'nullable', 'numeric'],
            'category_id' => ['sometimes', 'int', 'exists:categories,id'],
            'preview_image' => [
                'sometimes',
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value === null || $value instanceof Image) {
                        return;
                    }

                    if (!$value instanceof UploadedFile) {
                        $fail('Неверный тип файла');
                        return;
                    }

                    $valid = $value->isValid() &&
                        in_array($value->getMimeType(), ['image/jpeg', 'image/png', 'image/jpg', 'image/svg+xml']) &&
                        $value->getSize() <= 5120 * 1024;

                    if (!$valid) {
                        $fail('Файл должен быть изображением (jpeg,png,jpg,svg) не более 5MB');
                    }
                }
            ],
            'features' => ['sometimes', 'array'],
        ];
    }
}
