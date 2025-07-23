<?php

namespace App\Http\Controllers;

use App\Data\Products\ProductData;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CatalogController extends Controller
{
    public function show()
    {
        $products = Product::query()->withShop()->orderBy('id', 'desc')->get();

        return Inertia::render('Catalog', [
            'products' => ProductData::collect($products)
        ]);
    }

    public function showProduct($id)
    {
        $product = Product::query()->find($id);
    }
}
