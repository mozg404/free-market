<?php

namespace App\Http\Controllers\My\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\MyProduct\ProductCreateRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\Product\ProductService;
use App\Support\Price;
use Inertia\Inertia;

class ProductEditController extends Controller
{
    public function index(Product $product)
    {
        $product->load('category');
        $product->load('user');

        return Inertia::render('my/products/ProductEditPage', [
            'product' => $product,
        ]);
    }
}
