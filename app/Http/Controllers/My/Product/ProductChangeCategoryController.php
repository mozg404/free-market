<?php

namespace App\Http\Controllers\My\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\MyProduct\ProductChangeCategoryRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\Product\ProductService;
use App\Services\Toaster;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductChangeCategoryController extends Controller
{
    public function __construct(
        private readonly Toaster $toaster
    ) {
    }

    public function index(Product $product): Response
    {
        return Inertia::render('my/products/ProductChangeCategoryModal', [
            'product' => $product,
            'categoriesTree' => Category::query()->withDepth()->get()->toTree(),
        ]);
    }

    public function update(
        Product $product,
        ProductChangeCategoryRequest $request,
        ProductService $productService,
    ): RedirectResponse {
        $productService->changeCategory($product, $request->getModel());
        $this->toaster->success('Категория успешно изменена');

        return back();
    }
}
