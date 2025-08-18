<?php

namespace App\Http\Controllers\My\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\Product\ProductService;
use App\Services\Toaster;
use App\Support\Image;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductChangeImageController extends Controller
{
    public function __construct(
        private readonly Toaster $toaster
    ) {
    }

    public function index(Product $product): Response
    {
        return Inertia::render('common/ImageUploaderModal', [
            'imageUrl' => $product->image->getUrl(),
            'aspectRatio' => 3/4,
            'saveRoute' => route('my.products.change_image.update', $product->id),
            'product' => $product,
        ]);
    }

    public function update(
        Product $product,
        Request $request,
        ProductService $productService,
    ): RedirectResponse {
        $data = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,svg|max:5120',
        ]);
        $productService->changeImage($product, Image::createFromUploadedFile($data['image']));
        $this->toaster->success('Изображение сохранено');

        return back();
    }
}
