<?php

namespace App\Data\Products;

use App\Data\Categories\CategoryData;
use App\Data\FeatureData;
use App\Data\User\UserData;
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
        public bool $is_published,
        public bool $is_available,
        public ?int $available_stock_items_count,
        public ?CategoryData $category,
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
            is_published: $product->is_published,
            is_available: $product->is_available,
            available_stock_items_count: $product->getAvailableStockItemsCount() ?? 0,
            category: CategoryData::from($product->category),
            user: UserData::from($product->user),
            features: FeatureData::collect($product->features),
        );
    }
}
