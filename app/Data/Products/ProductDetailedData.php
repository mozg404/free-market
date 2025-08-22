<?php

namespace App\Data\Products;

use App\Data\Categories\CategorydData;
use App\Data\FeatureData;
use App\Data\User\UserData;
use App\Data\User\UserForListingData;
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
        public ?string $image_url,
        public ?string $description,
        public ?string $instruction,
        public int $positive_feedbacks_count,
        public int $negative_feedbacks_count,
        public float $rating,
        public ProductStatus $status,
        public ?int $available_stock_items_count,
        public ?CategorydData $category,
        public ?UserForListingData $user,
        public ?Collection $features = null,
    ) {
    }

    public static function fromModel(Product $product)
    {
        return new self(
            id: $product->id,
            name: $product->name,
            price: $product->price,
            image_url: $product->image_url,
            description: $product->description,
            instruction: $product->instruction,
            positive_feedbacks_count: $product->positive_feedbacks_count,
            negative_feedbacks_count: $product->negative_feedbacks_count,
            rating: $product->rating,
            status: $product->status,
            available_stock_items_count: $product->getQuantityInStock() ?? 0,
            category: CategorydData::from($product->category),
            user: UserForListingData::from($product->user),
            features: FeatureData::collect($product->features),
        );
    }
}
