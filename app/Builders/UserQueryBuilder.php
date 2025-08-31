<?php

declare(strict_types=1);

namespace App\Builders;

use InvalidArgumentException;
use App\Exceptions\Auth\UserNotFoundByEmailException;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserQueryBuilder extends Builder
{
    public function findByEmail(string $email): ?User
    {
        return $this->where('email', $email)->first();
    }

    public function getByEmail(string $email): User
    {
        $user = $this->findByEmail($email);

        if (!$user) {
            throw new InvalidArgumentException("User with email {$email} not found");
        }

        return $user;
    }

    public function withAvailableProductsCount(): self
    {
        return $this->withCount(['products as available_products_count' => function (ProductQueryBuilder $builder) {
            return $builder->isAvailable();
        }]);
    }

    public function hasAvailableProducts(): self
    {
        return $this->whereHas('products', function (ProductQueryBuilder $builder) {
            return $builder->isAvailable();
        });
    }

    public function checkExistsByEmail(string $email): bool
    {
        return $this->where('email', $email)->exists();
    }

    public function ensureExistsByEmail(string $email): void
    {
        if (!$this->checkExistsByEmail($email)) {
            throw new UserNotFoundByEmailException();
        }
    }
}
