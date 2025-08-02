<?php

declare(strict_types=1);

namespace App\Builders;

use App\Enum\OrderStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * @method Collection get(array|string $column = ['*'])
 */
class OrderItemQueryBuilder extends Builder
{
    public function whereUser(int $id): static
    {
        return $this->whereHas('order', function (OrderQueryBuilder $query) use ($id) {
            $query->whereUser($id);
        });
    }

    public function whereSeller(int $id): static
    {
        return $this->whereHas('stockItem', function (StockItemQueryBuilder $query) use ($id) {
            return $query->whereUser($id);
        });
    }
    
    public function isPaid(): static
    {
        return $this->whereHas('order', function (OrderQueryBuilder $query) {
            $query->isPaid();
        });
    }

    public function isNew(): static
    {
        return $this->whereHas('order', function (OrderQueryBuilder $query) {
            $query->isNew();
        });
    }

    public function descOrder(): static
    {
        return $this->orderByDesc('id');
    }

    public function withOrder(bool $withUser = true): static
    {
        if ($withUser) {
            return $this->with(['order', 'order.user']);
        }

        return $this->with('order');
    }

    public function withStockItem(): static
    {
        return $this->with('stockItem');
    }

    public function withProduct(bool $withUser = true): static
    {
        if ($withUser) {
            return $this->with(['stockItem.product', 'stockItem.product.user']);
        }

        return $this->with('stockItem.product');
    }
}
