<?php

namespace App\Http\Controllers\Cabinet;

use App\Data\Products\CreatingProductData;
use App\Data\Products\ProductData;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Shop;
use App\Services\ProductManager;
use App\Support\Filepond\Image;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductManager $products,
    ) {}

    public function index()
    {
        $products = $this->products->getList();

        return Inertia::render('cabinet/products/ProductList', [
            'products' => ProductData::collect($products),
        ]);
    }

    public function create()
    {
        return Inertia::render('cabinet/products/ProductCreate');
    }

    public function store(Request $request)
    {
        $this->products->create(CreatingProductData::validateAndCreate([
            'shopId' => 1,
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'priceDiscount' => $request->input('price_discount'),
            'image' => $request->input('image'),
            'isAvailable' => true,
        ]));

        return back();
    }

    public function show(Product $product)
    {
        //
    }

    public function edit(Product $product)
    {
        //
    }

    public function update(Request $request, Product $product)
    {
        //
    }

    public function destroy(Product $product)
    {
        //
    }
}
