<?php

declare(strict_types=1);

namespace App\Builders;

use App\Collections\ProductCollection;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * @method ProductCollection get(array|string $column = ['*'])
 */
class ProductQueryBuilder extends Builder
{
    /**
     * Выполняет поиск по характеристикам
     * Формат: [feature_id => [value1, value2, ...]]
     * @param array $filters
     * @return $this
     */
    public function whereFeatureValues(array $filters): self
    {
        foreach ($filters as $featureId => $values) {
            if (empty($values)) {
                continue;
            }

            if (!is_array($values)) {
                $values = [$values];
            }

            $this->whereHas('features', function ($query) use ($featureId, $values) {
                $query->where('features.id', $featureId)->whereIn('product_feature_values.value', (array)$values);
            });
        }

        return $this;
    }

    public function filterFromArray(array $data): self
    {
        if (isset($data['priceMin'])) {
            $this->wherePriceMin((int) $data['priceMin']);
        }

        if (isset($data['priceMax'])) {
            $this->wherePriceMax((int) $data['priceMax']);
        }

        if (isset($data['onlyDiscounted']) && in_array($data['onlyDiscounted'], ['true', '1', 1, true], true)) {
            $this->onlyDiscounted();
        }

        if (isset($data['features'])) {
            $this->whereFeatureValues($data['features']);
        }

        return $this;
    }

    /**
     * Фильтр по минимальной цене (с учетом скидки)
     */
    public function wherePriceMin(int $minPrice): self
    {
        return $this->where(function($query) use ($minPrice) {
            $query->where(function($q) use ($minPrice) {
                $q->whereNotNull('price_discount')
                    ->where('price_discount', '>=', $minPrice);
            })->orWhere(function($q) use ($minPrice) {
                $q->whereNull('price_discount')
                    ->where('price_base', '>=', $minPrice);
            });
        });
    }

    /**
     * Фильтр по максимальной цене (с учетом скидки)
     */
    public function wherePriceMax(int $maxPrice): self
    {
        return $this->where(function($query) use ($maxPrice) {
            $query->where(function($q) use ($maxPrice) {
                $q->whereNotNull('price_discount')
                    ->where('price_discount', '<=', $maxPrice);
            })->orWhere(function($q) use ($maxPrice) {
                $q->whereNull('price_discount')
                    ->where('price_base', '<=', $maxPrice);
            });
        });
    }

    /**
     * Фильтр по диапазону цен (с учетом скидки)
     */
    public function wherePriceBetween(?int $minPrice, ?int $maxPrice): self
    {
        return $this
            ->when($minPrice, fn($q) => $q->wherePriceMin($minPrice))
            ->when($maxPrice, fn($q) => $q->wherePriceMax($maxPrice));
    }

    /**
     * Только со скидками
     */
    public function onlyDiscounted(): self
    {
        return $this->whereNotNull('price_discount')
            ->where('price_discount', '<', DB::raw('price_base'));
    }

    /**
     * Сортировка по актуальной цене
     */
    public function orderByActualPrice(string $direction = 'asc'): self
    {
        return $this->orderByRaw('COALESCE(price_discount, price_base) ' . $direction);
    }

    public function forUser(User|int $user): static
    {
        if (is_object($user)) {
            $user = $user->id;
        }

        return $this->where('user_id', $user);
    }

    public function forCategory(Category|int $category): static
    {
        if (is_object($category)) {
            $category = $category->id;
        }

        return $this->where('category_id', $category);
    }

    public function for(User|Category $object): static
    {
        if (is_a($object, User::class)) {
            return $this->forUser($object);
        }

        if (is_a($object, Category::class)) {
            return $this->forCategory($object);
        }

        return $this;
    }

    public function withFeatures(): static
    {
        return $this->with('features');
    }

    /**
     * Включает количество позиций
     * @return $this
     */
    public function withStockItemsCount(): static
    {
        return $this->withCount('stockItems');
    }

    /**
     * Включает количество доступных позиций
     * @return $this
     */
    public function withAvailableStockItemsCount(): static
    {
        return $this->withCount(['stockItems as available_stock_items_count' => static function (StockItemQueryBuilder $builder) {
            return $builder->isAvailable();
        }]);
    }

    /**
     * Включает количество проданных позиций
     * @return $this
     */
    public function withSoldStockItemsCount(): static
    {
        return $this->withCount(['stockItems as sold_stock_items_count' => static function (StockItemQueryBuilder $builder) {
            return $builder->isSold();
        }]);
    }

    /**
     * Включает количество зарезервированных позиций
     * @return $this
     */
    public function withReservedStockItemsCount(): static
    {
        return $this->withCount(['stockItems as reserved_stock_items_count' => static function (StockItemQueryBuilder $builder) {
            return $builder->isReserved();
        }]);
    }

    /**
     * Сортировка по ID в обратном порядке
     * @return $this
     */
    public function descOrder(): static
    {
        return $this->orderByDesc('id');
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
