<?php

declare(strict_types=1);

namespace App\Builders;

use Illuminate\Database\Eloquent\Builder;

class UserQueryBuilder extends Builder
{
    public function withAvailableProductsCount(): self
    {
        return $this->withCount(['products as available_products_count' => function (ProductQueryBuilder $builder) {
            return $builder->isAvailable();
        }]);
    }

    public function hasAvailableProducts(): self
    {
        return $this->whereHas('products', function (ProductQueryBuilder $builder) {
            return $builder->isAvailable();
        });
    }
}
