<?php

namespace App\Http\Controllers\My\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\MyProduct\ProductChangeFeaturesRequest;
use App\Models\Feature;
use App\Models\Product;
use App\Services\Product\ProductService;
use App\Services\Toaster;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ProductChangeFeaturesController extends Controller
{
    public function __construct(
        private readonly Toaster $toaster
    ) {
    }

    public function index(Product $product): Response
    {
        return Inertia::render('my/products/ProductChangeFeaturesModal', [
            'product' => $product,
            'features' => Feature::query()->forCategoryAndAncestors($product->category)->get(),
            'featureValues' => $product->features->toIdValuePairs(),
        ]);
    }

    public function update(
        Product $product,
        ProductChangeFeaturesRequest $request,
        ProductService $productService,
    ): RedirectResponse {
        $productService->changeFeatures($product, $request->getValidatedValues());
        $this->toaster->success('Характеристики изменены');

        return back();
    }
}
