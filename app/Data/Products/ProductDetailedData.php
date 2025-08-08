<?php

namespace App\Data\Products;

use App\Data\Categories\CategoryData;
use App\Data\FeatureData;
use App\Data\ImageData;
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
        public ImageData $preview_image,
        public string $description,
        public ?int $stock_items_count,
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
            preview_image: ImageData::from($product->preview_image),
            description: $product->description,
            stock_items_count: $product->getAvailableStockItemsCount() ?? 0,
            category: CategoryData::from($product->category),
            user: UserData::from($product->user),
            features: FeatureData::collect($product->features),
        );
    }
}
