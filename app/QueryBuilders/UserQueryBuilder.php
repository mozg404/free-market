<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

class UserQueryBuilder extends Builder
{
    public function checkExistEmail(): bool
    {
        return false;
    }
}
