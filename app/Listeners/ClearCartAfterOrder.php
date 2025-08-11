<?php

namespace App\Listeners;

use App\Events\OrderCreatedFromCart;
use App\Services\Cart\CartManager;

class ClearCartAfterOrder
{
    public function __construct(
        private readonly CartManager $cartManager
    ) {}

    public function handle(OrderCreatedFromCart $event): void
    {
        $this->cartManager->clean();
    }
}
