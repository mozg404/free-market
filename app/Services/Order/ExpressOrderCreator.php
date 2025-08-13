<?php

namespace App\Services\Order;

use App\Collections\CreatableOrderItemCollection;
use App\Data\Orders\CreatableOrderItemData;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\Product\ProductManager;

class ExpressOrderCreator
{
    public function __construct(
        private ProductManager $productManager,
        private OrderCreator $creater
    )
    {}

    public function create(User $user, Product $product): Order
    {
        $this->productManager->ensureCanByPurchased($product);

        return $this->creater->create($user, new CreatableOrderItemCollection([
            new CreatableOrderItemData($product)
        ]));
    }
}