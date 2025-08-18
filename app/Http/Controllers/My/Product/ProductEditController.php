<?php

namespace App\Http\Controllers\My\Product;

use App\Data\Products\ProductDetailedData;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Inertia\Inertia;

class ProductEditController extends Controller
{
    public function index(Product $product)
    {
        return Inertia::render('my/products/ProductEditPage', [
            'product' => ProductDetailedData::from($product),
        ]);
    }
}
