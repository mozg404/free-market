<?php

declare(strict_types=1);

namespace App\Builders;

use App\Collections\OrderItemCollection;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method OrderItemCollection get(array|string $column = ['*'])
 */
class OrderItemQueryBuilder extends Builder
{
    public function forUser(User|int $user): static
    {
        if (is_object($user)) {
            $user = $user->id;
        }

        return $this->whereHas('order', function (OrderQueryBuilder $query) use ($user) {
            $query->whereUser($user);
        });
    }

    public function forOrder(Order|int $order): static
    {
        if (is_object($order)) {
            $order = $order->id;
        }

        return $this->where('order_id', $order);
    }

    public function for(Order|User $model): static
    {
        if (is_a($model, Order::class)) {
            return $this->forOrder($model);
        }

        if (is_a($model, User::class)) {
            return $this->forUser($model);
        }
    }

    public function whereSeller(int $id): static
    {
        return $this->whereHas('stockItem', function (StockItemQueryBuilder $query) use ($id) {
            return $query->forUser($id);
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

    public function withOrder(): static
    {
        return $this->with('order');
    }

    public function withOrderUser(): static
    {
        return $this->with('order.user');
    }

    public function withStockItem(): static
    {
        return $this->with('stockItem');
    }

    public function withProduct(): static
    {
        return $this->with('stockItem.product');
    }

    public function withProductUser(): static
    {
        return $this->with('stockItem.product.user');
    }
}
