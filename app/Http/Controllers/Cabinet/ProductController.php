<?php

namespace App\Http\Controllers\Cabinet;

use App\Data\Products\CreatingProductData;
use App\Data\Products\ProductData;
use App\Data\Products\UpdatingProductData;
use App\Data\Shops\ShopData;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Shop;
use App\Services\ProductManager;
use App\Services\Toaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductManager $products,
        private readonly Toaster $toaster,
    ) {}

    public function index(Request $request)
    {
        $products = Product::query()
            ->whereUser(Auth::id())
            ->orderBy('id', 'desc');

        if (!empty($request->input('search'))) {
            $products->searchByName($request->input('search'));
        }

        $products = $products->paginate(10);

        return Inertia::render('cabinet/products/ProductList', [
            'products' => ProductData::collect($products->items()),
            'links' => $products->toArray()['links'],
            'filters' => $request->only(['search', 'shop_id']),
        ]);
    }

    public function create()
    {
        return Inertia::render('cabinet/products/ProductCreate');
    }

    public function store(Request $request)
    {
        $this->products->create(CreatingProductData::validateAndCreate([
            ...$request->all(),
            'userId' => Auth::id(),
        ]));
        $this->toaster->success('Товар успешно создан');

        return redirect()->route('cabinet.products');
    }

    public function edit(Product $product)
    {
        return Inertia::render('cabinet/products/ProductUpdate', [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price_base,
            'priceDiscount' => $product->price_discount,
            'previewImage' => $product->preview_image->id,
            'isAvailable' => $product->is_available,
            'description' => $product->description,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $this->products->update($product, UpdatingProductData::validateAndCreate($request->all()));
        $this->toaster->success('Товар успешно изменен');

        return redirect()->route('cabinet.products');
    }

    public function destroy(Product $product)
    {
        $this->products->delete($product);
        $this->toaster->success('Товар успешно удален');

        return redirect()->route('cabinet.products');
    }
}
