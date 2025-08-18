<?php

namespace App\Http\Controllers\My\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\MyProduct\ProductChangePriceRequest;
use App\Models\Product;
use App\Services\Product\ProductService;
use App\Services\Toaster;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductChangePriceController extends Controller
{
    public function __construct(
        private readonly Toaster $toaster
    ) {
    }

    public function index(Product $product)
    {
        return Inertia::render('my/products/ProductChangePriceModal', [
            'product' => $product,
        ]);
    }

    public function update(
        Product $product,
        ProductChangePriceRequest $request,
        ProductService $productService,
    ) {
        $productService->changePrice($product, $request->getPrice());
        $this->toaster->success('Цена изменена');

        return back();
    }
}
