<?php

declare(strict_types=1);

namespace App\Builders;

use App\Models\Category;
use Illuminate\Support\Collection;
use Kalnoy\Nestedset\QueryBuilder;

class CategoryQueryBuilder extends QueryBuilder
{
    public function withFeatures(): static
    {
        return $this->with('features');
    }

    /**
     * Возвращает коллекцию ID для указанной категории + всех ее родителей
     */
    public function getAncestorAndSelfIds(Category|int $id): Collection
    {
        if ($id instanceof Category) {
            $id = $id->id;
        }

        return $this->ancestorsAndSelf($id)->pluck('id');
    }
}
