<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function view(User $user, Product $product): bool
    {
        return $user->id === $product->user_id;
    }

    public function update(User $user, Product $order): bool
    {
        return $user->id === $order->user_id;
    }
}
