<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

class ProductQueryBuilder extends Builder
{
    public function forUser(int $id): static
    {
        return $this->whereHas('shop', function (ShopQueryBuilder $query) use ($id) {
            return $query->forUser($id);
        });
    }

    public function withShop(): ProductQueryBuilder
    {
        return $this->with('shop');
    }
}
