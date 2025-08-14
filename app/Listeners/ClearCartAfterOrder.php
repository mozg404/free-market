<?php

namespace App\Listeners;

use App\Events\OrderCreatedFromCart;
use App\Services\Cart\CartService;

class ClearCartAfterOrder
{
    public function __construct(
        private readonly CartService $cartManager
    ) {}

    public function handle(OrderCreatedFromCart $event): void
    {
        $this->cartManager->clear();
    }
}
