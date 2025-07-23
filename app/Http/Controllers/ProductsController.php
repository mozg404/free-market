<?php

namespace App\Http\Controllers;

use App\Data\Products\ProductData;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductsController extends Controller
{
    public function list()
    {
        return 'Список';
    }

    public function show(Product $product)
    {
        return Inertia::render('ProductShow', [
            'product' => ProductData::from($product),
        ]);
    }
}
