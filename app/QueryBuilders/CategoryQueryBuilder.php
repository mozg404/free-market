<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

class CategoryQueryBuilder extends Builder
{
    public function withFeatures(): static
    {
        return $this->with('features');
    }
}
