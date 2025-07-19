<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

class ProductQueryBuilder extends Builder
{
    public function withShop(): ProductQueryBuilder
    {
        return $this->with('shop');
    }
}
