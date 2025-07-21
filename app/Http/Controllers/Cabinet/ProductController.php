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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductManager $products,
    ) {}

    public function index(Request $request)
    {
        $products = Product::query()
            ->withShop()
            ->whereUser(Auth::id())
            ->orderBy('id', 'desc');

        if (!empty($request->search)) {
            $products->searchByName($request->search);
        }

        if (!empty($request->shop_id)) {
            $products->whereShop($request->shop_id);
        }

        $products = $products->paginate(10);

        return Inertia::render('cabinet/products/ProductList', [
            'shops' => ShopData::collect(Shop::query()->forUser(Auth::id())->getNames()),
            'products' => ProductData::collect($products->items()),
            'links' => $products->toArray()['links'],
            'filters' => $request->only(['search', 'shop_id']),
        ]);
    }

    public function create()
    {
        $shops = Shop::query()->forUser(Auth::id())->getNames();

        return Inertia::render('cabinet/products/ProductCreate', [
            'shops' => $shops,
        ]);
    }

    public function store(Request $request)
    {
        $this->products->create(CreatingProductData::validateAndCreate($request->all()));

        return back();
    }

    public function edit(Product $product)
    {
        $shops = Shop::query()->forUser(Auth::id())->getNames();

        return Inertia::render('cabinet/products/ProductUpdate', [
            'shops' => $shops,
            'id' => $product->id,
            'shopId' => $product->shop_id,
            'name' => $product->name,
            'slug' => $product->slug,
            'price' => $product->price_base,
            'priceDiscount' => $product->price_discount,
            'image' => $product->preview_image->id,
            'isAvailable' => $product->is_available,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $this->products->update($product, UpdatingProductData::validateAndCreate($request->all()));

        return back();
    }

    public function destroy(Product $product)
    {
        $this->products->delete($product);

        return back();
    }
}
