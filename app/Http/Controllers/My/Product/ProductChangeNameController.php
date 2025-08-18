<?php

namespace App\Http\Controllers\My\Product;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\Product\ProductService;
use App\Services\Toaster;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductChangeNameController extends Controller
{
    public function __construct(
        private readonly Toaster $toaster
    ) {
    }

    public function index(Product $product)
    {
        return Inertia::render('my/products/ProductChangeNameModal', [
            'product' => $product,
        ]);
    }

    public function update(
        Product $product,
        Request $request,
        ProductService $productService,
    ) {
        $productService->changeName($product, $request->input('name'));
        $this->toaster->success('Название отредактировано');

        return back();
    }
}
