<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductManager
{
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