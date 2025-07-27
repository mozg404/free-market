<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use App\Enum\StockItemStatus;
use App\Models\User;
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

    public function whereUser(User|int $id): static
    {
        if (is_object($id)) {
            $id = $id->id;
        }

        return $this->whereHas('product', function (ProductQueryBuilder $query) use ($id) {
            $query->whereUser($id);
        });
    }
}
