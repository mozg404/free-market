<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use App\Enum\OrderStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * @method Collection get(array|string $column = ['*'])
 */
class OrderQueryBuilder extends Builder
{
    public function whereUser(User|int $id): static
    {
        if (is_object($id)) {
            $id = $id->id;
        }

        return $this->where('user_id', $id);
    }

    public function isPaid(): static
    {
        return $this->where('status', OrderStatus::PAID);
    }

    public function isNew(): static
    {
        return $this->where('status', OrderStatus::NEW);
    }
}
