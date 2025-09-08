<?php

namespace App\Services\User;

use App\Builders\UserQueryBuilder;
use App\Models\User;

class UserQuery
{
    public function query(): UserQueryBuilder
    {
        return User::query();
    }
}