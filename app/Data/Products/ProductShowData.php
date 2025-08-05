<?php

namespace App\Data\Products;

use App\Data\FeatureData;
use App\Data\ImageData;
use App\Data\UserData;
use App\Models\Product;
use App\Models\User;
use App\Support\Price;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class ProductShowData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public Price $price,
        public ImageData $previewImage,
        public string $description,
        public int $stockItemsCount,
        public UserData $seller,
        public Collection|null $features = null,
    ) {
    }

    public static function fromModel(Product $product): self
    {
        return new self(
            id: $product->id,
            name: $product->name,
            price: $product->price,
            previewImage: ImageData::from($product->preview_image),
            description: $product->description,
            stockItemsCount: $product->stock_items_count ?? 0,
            seller: UserData::from($product->user),
            features: FeatureData::collect($product->features),
        );
    }
}
