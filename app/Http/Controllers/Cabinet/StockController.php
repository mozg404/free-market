<?php

namespace App\Http\Controllers\Cabinet;

use App\Data\Products\ProductData;
use App\Data\Products\StockItemData;
use App\Data\Products\StockItemFullData;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockItem;
use App\Services\StockManager;
use App\Services\Toaster;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StockController extends Controller
{
    public function __construct(
        private readonly StockManager $stock,
        private readonly Toaster      $toaster,
    )
    {}

    public function index(Product $product)
    {
        return Inertia::render('cabinet/products/StockItemList', [
            'product' => ProductData::from($product),
            'stockItems' => StockItemFullData::collect($product->stockItems()->orderByDesc('id')->get()),
        ]);
    }

    public function create(Product $product, Request $request)
    {
        return Inertia::render('cabinet/products/StockItemCreate', [
            'product' => ProductData::from($product),
        ]);
    }

    public function store(Product $product, Request $request)
    {
        $data = $request->validate([
            'content' => 'required|string|min:5',
        ]);

        $stockItem = $this->stock->addItemTo($product, $data['content']);
        $this->toaster->success("Позиция успешно добавлена");

        return back();
    }

    public function edit(StockItem $stockItem)
    {
        return Inertia::render('cabinet/products/StockItemUpdate', [
            'id' => $stockItem->id,
            'content' => $stockItem->content,
        ]);
    }

    public function update(StockItem $stockItem, Request $request)
    {
        $data = $request->validate([
            'content' => 'required|string|min:5',
        ]);

        $this->stock->updateItem($stockItem, $data['content']);
        $this->toaster->success("Позиция #$stockItem->id успешно изменена");

        return back();
    }

    public function destroy(StockItem $stockItem)
    {
        $this->stock->delete($stockItem);
        $this->toaster->success("Позиция #$stockItem->id успешно удалена");

        return back();
    }
}
