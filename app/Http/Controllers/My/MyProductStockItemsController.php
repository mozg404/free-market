<?php

namespace App\Http\Controllers\My;

use App\Data\Products\ProductForListingData;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockItem;
use App\Services\Toaster;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MyProductStockItemsController extends Controller
{
    public function __construct(
        private readonly Toaster $toaster,
    ) {
    }

    public function create(Product $product, Request $request)
    {
        return Inertia::render('my/products/StockItemCreateModal', [
            'product' => ProductForListingData::from($product),
        ]);
    }

    public function store(Product $product, Request $request)
    {
        $data = $request->validate([
            'content' => 'required|string|max::255',
        ]);

        StockItem::new($product, $data['content']);
        $this->toaster->success("Позиция успешно добавлена");

        return back();
    }

    public function edit(Product $product, StockItem $stockItem)
    {
        return Inertia::render('my/products/StockItemEditModal', [
            'stockItem' => $stockItem,
            'product' => $product,
        ]);
    }

    public function update(Product $product, StockItem $stockItem, Request $request)
    {
        $data = $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $stockItem->edit($data['content']);
        $this->toaster->success("Позиция #$stockItem->id успешно изменена");

        return back();
    }
}
