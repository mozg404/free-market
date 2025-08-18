<?php

namespace App\Http\Controllers\My\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\MyProduct\ProductCreateRequest;
use App\Models\Category;
use App\Services\Product\ProductService;
use App\Support\Price;
use Inertia\Inertia;

class ProductCreateController extends Controller
{
    public function index()
    {
        return Inertia::render('my/products/ProductCreateModal', [
            'categoriesTree' => Category::query()->withDepth()->get()->toTree(),
        ]);
    }

    public function store(ProductCreateRequest $request, ProductService $productService)
    {
        $product = $productService->createProduct(
            user: auth()->user(),
            category: Category::find($request->input('category_id')),
            name: $request->input('name'),
            price: Price::fromBaseAndDiscount($request->input('price_base'), $request->input('price_discount'))
        );

        return redirect()->route('my.products.show', ['product' => $product]);
    }
}
