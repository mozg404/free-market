<?php

declare(strict_types=1);

namespace App\Builders;

use App\Collections\ProductCollection;
use App\Enum\ProductStatus;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
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
        if (isset($data['price_min'])) {
            $this->wherePriceMin((int) $data['price_min']);
        }

        if (isset($data['price_max'])) {
            $this->wherePriceMax((int) $data['price_max']);
        }

        if (isset($data['is_discounted']) && in_array($data['is_discounted'], ['true', '1', 1, true], true)) {
            $this->isDiscounted();
        }

        if (isset($data['features'])) {
            $this->whereFeatureValues($data['features']);
        }

        if (isset($data['status'])) {
            if ($data['status'] === 'available') {
                $this->isAvailable();
            }

            if ($data['status'] === 'sold_out') {
                $this->isSoldOut();
            }

            if ($data['status'] === 'draft') {
                $this->isDraft();
            }

            if ($data['status'] === 'paused') {
                $this->isPaused();
            }
        }

        if (!empty($data['search'])) {
            $this->searchByName($data['search']);
        }

        if (isset($data['sort'])) {
            if ($data['sort'] === 'rating') {
                $this->orderByRating();
            }

            if ($data['sort'] === 'latest') {
                $this->latest();
            }

            if ($data['sort'] === 'oldest') {
                $this->oldest();
            }

            if ($data['sort'] === 'price_desc') {
                $this->orderByDesc('current_price');
            }

            if ($data['sort'] === 'price_asc') {
                $this->orderBy('current_price');
            }

            if ($data['sort'] === 'id_desc') {
                $this->orderByDesc('id');
            }

            if ($data['sort'] === 'id_asc') {
                $this->orderBy('id');
            }
        }

        return $this;
    }

    public function isAvailable(): self
    {
        return $this->hasAvailableStock()->isActive();
    }

    public function isSoldOut(): self
    {
        return $this->hasNoAvailableStock()->isActive();
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
    public function isDiscounted(): self
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

    /**
     * НЕ имеет доступных позиций для продажи
     */
    public function hasNoAvailableStock(): self
    {
        return $this->whereDoesntHave('stockItems', fn (StockItemQueryBuilder $builder) => $builder->isAvailable());
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

    public function whereNotBelongsToUser(int|User $user): self
    {
        if (is_object($user)) {
            $user = $user->id;
        }

        return $this->where('user_id', '!=', $user);
    }

    public function forCategoryAndDescendants(Category|int $id): self
    {
        if ($id instanceof Category) {
            $id = $id->id;
        }

        return $this->forCategories(
            Category::query()->getDescendantsAndSelfIds($id)->toArray()
        );
    }

    public function forCategories(array $ids): self
    {
        return $this->whereIn('category_id', $ids);
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
        return $this->whereRaw('name LIKE ?', ['%' . $search . '%']);
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
    // Order
    // -----------------------------------------------

    public function orderByRating(): self
    {
        return $this->orderByDesc('rating');
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
