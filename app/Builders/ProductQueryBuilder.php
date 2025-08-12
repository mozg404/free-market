<?php

declare(strict_types=1);

namespace App\Builders;

use App\Collections\ProductCollection;
use App\Enum\ProductStatus;
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

    public function isActive(): self
    {
        return $this->where('status', ProductStatus::ACTIVE->value);
    }

    public function isDraft(): self
    {
        return $this->where('status', ProductStatus::DRAFT->value);
    }

    public function isPaused(): self
    {
        return $this->where('status', ProductStatus::PAUSED->value);
    }

    public function isPublished(): self
    {
        return $this->where('is_published', true);
    }

    /**
     * Фильтр по минимальной цене (current_price)
     */
    public function wherePriceMin(float $minPrice): self
    {
        return $this->where('current_price', '>=', $minPrice);
    }

    /**
     * Фильтр по максимальной цене (current_price)
     */
    public function wherePriceMax(float $maxPrice): self
    {
        return $this->where('current_price', '<=', $maxPrice);
    }

    /**
     * Только товары со скидкой (current_price < base_price)
     */
    public function onlyDiscounted(): self
    {
        return $this->whereColumn('current_price', '<', 'base_price');
    }

    /**
     * Имеет доступные для продажи позиции
     */
    public function hasAvailableStock(): self
    {
        return $this->whereHas('stockItems', fn (StockItemQueryBuilder $builder) => $builder->isAvailable());
    }

    public function canByPurchased(): self
    {
        return $this->hasAvailableStock()->isActive();
    }

    /**
     * Имеет позиции на складе
     */
    public function hasStockItems(): self
    {
        return $this->whereHas('stockItems');
    }
    
    /**
     * Сортировка по актуальной цене
     */
    public function orderByActualPrice(string $direction = 'asc'): self
    {
        return $this->orderByRaw('COALESCE(price_discount, price_base) ' . $direction);
    }

    public function forUser(User|int $user): self
    {
        if (is_object($user)) {
            $user = $user->id;
        }

        return $this->where('user_id', $user);
    }

    public function forCategory(Category|int $category): self
    {
        if (is_object($category)) {
            $category = $category->id;
        }

        return $this->where('category_id', $category);
    }

    public function for(User|Category $object): self
    {
        if (is_a($object, User::class)) {
            return $this->forUser($object);
        }

        if (is_a($object, Category::class)) {
            return $this->forCategory($object);
        }

        return $this;
    }

    /**
     * Оставляет только те строки, подстрока $search содержится в названии
     * @param string $search
     * @return $this
     */
    public function searchByName(string $search): self
    {
        return $this->where('name', 'like', '%' . $search . '%');
    }

    /**
     * Возвращает перечень товаров массиву ID
     * @param array $ids
     * @return $this
     */
    public function whereIds(array $ids): self
    {
        return $this->whereIn('id', $ids);
    }

    public function withFeatures(): self
    {
        return $this->with('features');
    }

    /**
     * Включает количество позиций
     * @return $this
     */
    public function withStockItemsCount(): self
    {
        return $this->withCount('stockItems');
    }

    /**
     * Включает количество доступных позиций
     * @return $this
     */
    public function withAvailableStockItemsCount(): self
    {
        return $this->withCount(['stockItems as available_stock_items_count' => static function (StockItemQueryBuilder $builder) {
            return $builder->isAvailable();
        }]);
    }

    /**
     * Включает количество проданных позиций
     * @return $this
     */
    public function withSoldStockItemsCount(): self
    {
        return $this->withCount(['stockItems as sold_stock_items_count' => static function (StockItemQueryBuilder $builder) {
            return $builder->isSold();
        }]);
    }

    /**
     * Включает количество зарезервированных позиций
     * @return $this
     */
    public function withReservedStockItemsCount(): self
    {
        return $this->withCount(['stockItems as reserved_stock_items_count' => static function (StockItemQueryBuilder $builder) {
            return $builder->isReserved();
        }]);
    }

    // -----------------------------------------------
    // Combined
    // -----------------------------------------------

    /**
     * Возвращает только активные опубликованные товары с доступными для продажи позициями
     */
    public function forListing(): self
    {
        return $this
            ->isActive()
            ->hasAvailableStock();
    }
}
