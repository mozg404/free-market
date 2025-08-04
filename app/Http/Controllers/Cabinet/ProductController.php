<?php

namespace App\Http\Controllers\Cabinet;

use App\Data\Products\BaseProductData;
use App\Data\Products\CreatingProductData;
use App\Data\Products\ProductData;
use App\Data\Products\UpdatingProductData;
use App\Data\Shops\ShopData;
use App\Http\Controllers\Controller;
use App\Models\Category;
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
            ->forUser(Auth::id())
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
        $categories = Category::query()
            ->withFeatures()
            ->get();

        return Inertia::render('cabinet/products/ProductEdit', [
            'categories' => $categories,
            'testChecks' => ['on1' => 'Значение 1', 'on2' => 'Значение 2', 'on3' => 'Значение 3'],
        ]);
    }

    public function store(Request $request)
    {
        dd($request->all());
        Product::new(Auth::user(), BaseProductData::validateAndCreate($request));
        $this->toaster->success('Товар успешно создан');

        return redirect()->route('cabinet.products');
    }

    public function edit(Product $product)
    {
        $categories = Category::query()
            ->withFeatures()
            ->get();

        return Inertia::render('cabinet/products/ProductEdit', [
            'categories' => $categories,
            'isEdit' => true,
            'id' => $product->id,
            'data' => BaseProductData::from($product),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $product->edit(BaseProductData::validateAndCreate($request));
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
