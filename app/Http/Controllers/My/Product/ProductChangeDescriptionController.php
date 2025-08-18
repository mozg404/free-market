<?php

namespace App\Http\Controllers\My\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\MyProduct\ProductChangeDescriptionRequest;
use App\Models\Product;
use App\Services\Product\ProductService;
use App\Services\Toaster;
use Inertia\Inertia;

class ProductChangeDescriptionController extends Controller
{
    public function __construct(
        private readonly Toaster $toaster
    ) {
    }

    public function index(Product $product)
    {
        return Inertia::render('my/products/ProductChangeDescriptionModal', [
            'product' => $product,
        ]);
    }

    public function update(
        Product $product,
        ProductChangeDescriptionRequest $request,
        ProductService $productService,
    ) {
        $productService->changeDescription($product, $request->input('description'));
        $this->toaster->success('Описание изменено');

        return back();
    }
}
