<?php

declare(strict_types=1);

namespace App\Builders;

use Illuminate\Database\Eloquent\Builder;

class UserQueryBuilder extends Builder
{
    public function withAvailableProductsCount(): self
    {
        return $this->withCount(['products as available_products_count' => function (ProductQueryBuilder $builder) {
            return $builder->canByPurchased();
        }]);
    }

    public function hasAvailableProducts()
    {
        return $this->whereHas('products', function (ProductQueryBuilder $builder) {
            return $builder->canByPurchased();
        });
    }

    public function withSoldCount(): self
    {
        return $this->withCount(['products as sold_stock_count' => function (ProductQueryBuilder $query) {
            $query->whereHas('stockItems', fn (StockItemQueryBuilder $q) => $q->isSold());
        }]);
    }
}
