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
        $product->shop_id = $data->shopId;
        $product->name = $data->name;
        $product->slug = Str::slug($data->name);
        $product->price = $data->price;
        $product->price_discount = $data->priceDiscount;
        $product->is_available = $data->isAvailable;
        $product->image = $data->image->publishIfTemporary();

        try {
            $product->save();
        } catch (\Exception $exception) {
            $product->image->delete();

            throw $exception;
        }

        return $product;
    }

    public function update(Product $product, UpdatingProductData $data): Product
    {
        $product->shop_id = $data->shopId;
        $product->name = $data->name;
        $product->slug = $data->slug;
        $product->price = $data->price;
        $product->price_discount = $data->priceDiscount;
        $product->is_available = $data->isAvailable;
        $product->image = $data->image->publishIfTemporary();

        if (empty($data->slug)) {
            $product->slug = Str::slug($data->name);
        }

        try {
            $product->save();
        } catch (\Exception $exception) {
            $product->image->delete();

            throw $exception;
        }

        return $product;
    }
    
    public function getList(): Collection
    {
        return Product::query()->withShop()->orderBy('id', 'desc')->get();
    }
}