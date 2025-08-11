<?php

declare(strict_types=1);

namespace App\Builders;

use App\Enum\StockItemStatus;
use App\Models\Product;
use App\Models\StockItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * @method Collection|StockItem[] get(array|string $column = ['*'])
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

    public function forProduct(int|Product $id): static
    {
        if (is_object($id)) {
            $id = $id->id;
        }

        return $this->where('product_id', $id);
    }

    public function forUser(int|User $id): static
    {
        if (is_object($id)) {
            $id = $id->id;
        }

        return $this->whereHas('product', function (ProductQueryBuilder $query) use ($id) {
            $query->forUser($id);
        });
    }

    public function withPinnedUser(): static
    {
        return $this->with('pinnedUser');
    }

    public function forPinnedUser(int|User $id): static
    {
        if (is_object($id)) {
            $id = $id->id;
        }

        return $this->where('pinned_user_id', $id);
    }
}
