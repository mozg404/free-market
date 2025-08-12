<?php

namespace App\Data\Products;

use App\Data\Categories\CategorydData;
use App\Data\FeatureData;
use App\Data\User\UserData;
use App\Enum\ProductStatus;
use App\Models\Product;
use App\Support\Price;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class ProductDetailedData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public Price $price,
        public ?string $preview_image,
        public ?string $description,
        public ?string $instruction,
        public ProductStatus $status,
        public ?int $available_stock_items_count,
        public ?CategorydData $category,
        public ?UserData $user,
        public ?Collection $features = null,
    ) {
    }

    public static function fromModel(Product $product)
    {
        return new self(
            id: $product->id,
            name: $product->name,
            price: $product->price,
            preview_image: $product->preview_image,
            description: $product->description,
            instruction: $product->instruction,
            status: $product->status,
            available_stock_items_count: $product->getQuantityInStock() ?? 0,
            category: CategorydData::from($product->category),
            user: UserData::from($product->user),
            features: FeatureData::collect($product->features),
        );
    }
}
