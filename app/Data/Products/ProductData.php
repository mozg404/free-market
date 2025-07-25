<?php

declare(strict_types=1);

namespace App\Data\Products;

use App\Data\ImageData;
use App\Models\Product;
use App\Support\Price;
use Spatie\LaravelData\Data;

class ProductData extends Data
{
    public string $imageUrl;

    public function __construct(
        public int       $id,
        public string    $name,
        public bool      $isAvailable,
        public Price     $price,
        public ImageData $previewImage,
        public int       $stockItemsCount,

    ){}

    public static function fromModel(Product $product): self
    {
        return new self(
            id: $product->id,
            name: $product->name,
            isAvailable: $product->is_available,
            price: $product->price,
            previewImage: isset($product->preview_image)
                ? new ImageData(true, $product->preview_image->getUrl())
                : new ImageData(false),
            stockItemsCount: $product->stock_items_count ?? 0,
        );
    }
}
