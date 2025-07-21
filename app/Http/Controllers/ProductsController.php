<?php

namespace App\Http\Controllers;

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
            'id' => $product->id,
            'name' => $product->name,
            'imageUrl' => Storage::url($product->preview_image)
        ]);
    }
}
