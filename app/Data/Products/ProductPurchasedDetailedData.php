<?php

namespace App\Data\Products;

use App\Models\StockItem;
use Spatie\LaravelData\Data;

class ProductPurchasedDetailedData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $content,
        public ?string $instruction,
    ) {}

    public static function fromModel(StockItem $stockItem): self
    {
        return new self(
            id: $stockItem->id,
            name: $stockItem->product->name,
            content: $stockItem->content,
            instruction: $stockItem->product->instruction,
        );
    }
}
