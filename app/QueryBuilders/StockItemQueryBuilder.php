<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use App\Enum\StockItemStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * @method Collection get(array|string $column = ['*'])
 */
class StockItemQueryBuilder extends Builder
{
    public function isAvailable(): static
    {
        return $this->where('status', StockItemStatus::AVAILABLE->value);
    }

    public function isReserved(): static
    {
        return $this->where('status', StockItemStatus::RESERVED->value);
    }

    public function isSold(): static
    {
        return $this->where('status', StockItemStatus::SOLD->value);
    }
}
