<?php

declare(strict_types=1);

namespace App\Data\Products;

use App\Data\ImageData;
use App\Data\Shops\ShopData;
use App\Models\Product;
use App\Support\Price;
use Spatie\LaravelData\Data;

class ProductData extends Data
{
    public string $imageUrl;

    public function __construct(
        public int   $id,
        public string    $name,
        public bool      $isAvailable,
        public Price     $price,
        public ImageData $previewImage,
        public ShopData  $shop,
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
            shop: new ShopData(
                $product->shop->id,
                $product->shop->name,
            )
        );
    }
}
