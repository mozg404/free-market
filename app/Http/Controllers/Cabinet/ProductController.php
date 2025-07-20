<?php

namespace App\Http\Controllers\Cabinet;

use App\Data\Products\CreatingProductData;
use App\Data\Products\ProductData;
use App\Data\Products\UpdatingProductData;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ProductManager;
use Illuminate\Http\Request;
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
        $preoduct = $this->products->create(CreatingProductData::validateAndCreate([
            'shopId' => 1,
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'priceDiscount' => $request->input('priceDiscount'),
            'image' => $request->input('image'),
            'isAvailable' => $request->input('isAvailable'),
        ]));

        return back();
    }

    public function edit(Product $product)
    {
        return Inertia::render('cabinet/products/ProductUpdate', [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'price' => $product->price,
            'priceDiscount' => $product->price_discount,
            'image' => $product->image->path,
            'isAvailable' => $product->is_available,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $this->products->update($product, UpdatingProductData::validateAndCreate([
            'shopId' => 1,
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
            'price' => $request->input('price'),
            'priceDiscount' => $request->input('priceDiscount'),
            'image' => $request->input('image'),
            'isAvailable' => $request->input('isAvailable'),
        ]));

        return back();
    }

    public function destroy(Product $product)
    {
        //
    }
}
