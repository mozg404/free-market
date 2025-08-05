<?php

declare(strict_types=1);

namespace App\Data\Products;

use App\Data\ImageData;
use App\Enum\OrderStatus;
use App\Enum\StockItemStatus;
use App\Models\Product;
use App\Models\StockItem;
use App\Support\Price;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

class StockItemFullData extends Data
{
    public function __construct(
        public int $id,
        public int $productId,
        public StockItemStatus $status,
        public string $content,
        public bool $isAvailable,
        public bool $isReserved,
        public bool $isSold,
        public Carbon $createdAt,
        public Carbon $updatedAt,
    ){}

    public static function fromModel(StockItem $stockItem): self
    {
        return new self(
            id: $stockItem->id,
            productId: $stockItem->product_id,
            status: $stockItem->status,
            content: $stockItem->content,
            isAvailable: $stockItem->isAvailable(),
            isReserved: $stockItem->isReserved(),
            isSold: $stockItem->isSold(),
            createdAt: $stockItem->created_at,
            updatedAt: $stockItem->updated_at,
        );
    }
}
