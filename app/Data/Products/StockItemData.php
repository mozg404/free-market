<?php

declare(strict_types=1);

namespace App\Data\Products;

use App\Data\User\UserData;
use App\Enum\StockItemStatus;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

class StockItemData extends Data
{
    public function __construct(
        public int $id,
        public int $product_id,
        public StockItemStatus $status,
        public string $content,
        public ?UserData $pinnedUser = null,
        public Carbon $created_at,
        public Carbon $updated_at,
    ){}
}
