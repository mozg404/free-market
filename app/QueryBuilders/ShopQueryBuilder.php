<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ShopQueryBuilder extends Builder
{
    public function forUser(int $id): static
    {
        return $this->where('user_id', $id);
    }

    public function forUsers(array $ids): static
    {
        return $this->whereIn('user_id', $ids);
    }

    public function getNames(): Collection
    {
        return $this->get(['id', 'name']);
    }

    public function withUser(): ShopQueryBuilder
    {
        return $this->with('user');
    }
}
