<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use App\Collections\ProductCollection;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method ProductCollection get(array|string $column = ['*'])
 */
class ProductQueryBuilder extends Builder
{
    public function forUser(int $id): static
    {
        return $this->whereHas('shop', function (ShopQueryBuilder $query) use ($id) {
            return $query->forUser($id);
        });
    }

    /**
     * Возвращает коллекцию объектов моделей с ценами
     * @return ProductCollection
     */
    public function getPrices(): ProductCollection
    {
        return $this->get(['id', 'price', 'price_discount']);
    }

    /**
     * Возвращает перечень товаров массиву ID
     * @param array $ids
     * @return $this
     */
    public function whereIds(array $ids): static
    {
        return $this->whereIn('id', $ids);
    }

    public function withShop(): ProductQueryBuilder
    {
        return $this->with('shop');
    }
}
