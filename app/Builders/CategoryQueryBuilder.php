<?php

declare(strict_types=1);

namespace App\Builders;

use Kalnoy\Nestedset\QueryBuilder;

class CategoryQueryBuilder extends QueryBuilder
{
    public function withFeatures(): static
    {
        return $this->with('features');
    }
}
