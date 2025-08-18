<?php

namespace App\Http\Controllers\My\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\MyProduct\ProductChangeInstructionRequest;
use App\Models\Product;
use App\Services\Product\ProductService;
use App\Services\Toaster;
use Inertia\Inertia;

class ProductChangeInstructionController extends Controller
{
    public function __construct(
        private readonly Toaster $toaster
    ) {
    }

    public function index(Product $product)
    {
        return Inertia::render('my/products/ProductChangeInstructionModal', [
            'product' => $product,
        ]);
    }

    public function update(
        Product $product,
        ProductChangeInstructionRequest $request,
        ProductService $productService,
    ) {
        $productService->changeInstruction($product, $request->input('instruction'));
        $this->toaster->success('Инструкция изменена');

        return back();
    }
}
