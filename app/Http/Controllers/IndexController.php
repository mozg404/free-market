<?php

namespace App\Http\Controllers;

use App\Data\Products\ProductData;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IndexController extends Controller
{
    public function __invoke()
    {
        $products = Product::query()->take(10)->get();

        return Inertia::render('Index', [
            'products' => ProductData::collect($products)
        ]);
    }
}
