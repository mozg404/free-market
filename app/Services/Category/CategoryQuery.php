<?php

namespace App\Services\Category;

use App\Builders\CategoryQueryBuilder;
use App\Exceptions\Category\CategoryNotFoundException;
use App\Models\Category;

class CategoryQuery
{
    public function query(): CategoryQueryBuilder
    {
        return Category::query();
    }

    public function getByFullPath(string $fullPath): Category
    {
        $category = $this->query()->whereFullPath($fullPath)->first();

        if (!isset($category)) {
            throw new CategoryNotFoundException();
        }

        return $category;
    }
}