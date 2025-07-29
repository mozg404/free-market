<?php

namespace App\Services;

use App\Data\Products\CreatingProductData;
use App\Data\Products\UpdatingProductData;
use App\Models\Product;
use App\Models\Shop;
use App\Support\Filepond\Image;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class ProductManager
{
    /**
     * @throws \ErrorException
     */
    public function create(CreatingProductData $data): Product
    {
        $product = new Product();
        $product->user_id = $data->userId;
        $product->name = $data->name;
        $product->price_base = $data->price;
        $product->price_discount = $data->priceDiscount;
        $product->is_available = $data->isAvailable;
        $product->preview_image = $data->previewImage->publishIfTemporary();
        $product->description = $data->description;

        try {
            $product->save();
        } catch (\Exception $exception) {
            $product->preview_image->delete();

            throw $exception;
        }

        return $product;
    }

    public function update(Product $product, UpdatingProductData $data): Product
    {
        $product->name = $data->name;
        $product->price_base = $data->price;
        $product->price_discount = $data->priceDiscount;
        $product->is_available = $data->isAvailable;
        $product->preview_image = $data->previewImage->publishIfTemporary();
        $product->description = $data->description;

        try {
            $product->save();
        } catch (\Exception $exception) {
            $product->preview_image->delete();

            throw $exception;
        }

        return $product;
    }

    public function delete(Product $product): void
    {
        $product->delete();
        $product->preview_image->delete();
    }

    public function getList(): Collection
    {
        return Product::query()->withShop()->orderBy('id', 'desc')->get();
    }
}