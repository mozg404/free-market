<?php

namespace App\Http\Controllers\My;

use App\Data\Products\ProductDetailedData;
use App\Data\Products\ProductEditableData;
use App\Data\Products\ProductForListingData;
use App\Data\Products\StockItemData;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\Toaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MyProductController extends Controller
{
    public function __construct(
        private readonly Toaster $toaster,
    ) {}

    public function index(Request $request)
    {
        $products = Product::query()
            ->forUser(Auth::id())
            ->withStockItemsCount()
            ->withAvailableStockItemsCount()
            ->orderBy('id', 'desc');

        if (!empty($request->input('search'))) {
            $products->searchByName($request->input('search'));
        }

        $products = $products->paginate(10);

        return Inertia::render('my/products/ProductIndexPage', [
            'products' => ProductForListingData::collect($products->items()),
            'links' => $products->toArray()['links'],
            'filters' => $request->only(['search', 'shop_id']),
        ]);
    }

    public function show(Product $product, Request $request)
    {
//        return ProductDetailedData::from($product);

        return Inertia::render('my/products/ProductShowPage', [
            'product' => ProductDetailedData::from($product),
            'itemsPaginated' => StockItemData::collect($product->stockItems()->orderByDesc('id')->paginate(10)),
            'availableItemsCount' => $product->stockItems()->isAvailable()->count(),
            'soldItemsCount' => $product->stockItems()->isSold()->count(),
            'reservedItemsCount' => $product->stockItems()->isReserved()->count(),
        ]);
    }

    public function create()
    {
        $categories = Category::query()
            ->withFeatures()
            ->get();

        return Inertia::render('my/products/ProductCreateAndEditPage', [
            'categories' => $categories,
        ]);
    }

    public function store(ProductEditableData $data)
    {
        Product::new(Auth::user(), $data);
        $this->toaster->success('Товар успешно создан');

        return redirect()->route('my.products');
    }

    public function edit(Product $product)
    {
        $categories = Category::query()
            ->withFeatures()
            ->get();

        return Inertia::render('my/products/ProductCreateAndEditPage', [
            'categories' => $categories,
            'isEdit' => true,
            'id' => $product->id,
            'data' => ProductEditableData::from($product),
        ]);
    }

    public function update(ProductEditableData $data, Product $product)
    {
        $product->edit($data);
        $this->toaster->success('Товар успешно изменен');

        return redirect()->route('my.products');
    }

    public function publish(Product $product)
    {
        $product->publish();
        $this->toaster->success('Опубликовано');

        return back();
    }

    public function unpublish(Product $product)
    {
        $product->unpublish();
        $this->toaster->error('Снято с публикации');

        return back();
    }

    public function markAsAvailable(Product $product)
    {
        $product->markAsAvailable();
        $this->toaster->success('Разрешено для продажи');

        return back();
    }

    public function markAsUnavailable(Product $product)
    {
        $product->markAsUnavailable();
        $this->toaster->success('Снято с продажи');

        return back();
    }

    public function destroy(Product $product)
    {
//        $this->products->delete($product);
        $this->toaster->success('Товар успешно удален');

        return redirect()->route('my.products');
    }
}
