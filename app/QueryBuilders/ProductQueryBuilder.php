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
    public function whereUser(int $id): static
    {
        return $this->where('user_id', $id);
    }

    /**
     * Оставляет только те строки, подстрока $search содержится в названии
     * @param string $search
     * @return $this
     */
    public function searchByName(string $search): static
    {
        return $this->where('name', 'like', '%' . $search . '%');
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
