<?php

namespace App\Services\Order;

use App\Collections\CreatableOrderItemCollection;
use App\Data\Orders\CreatableOrderItemData;
use App\Events\OrderCreatedFromCart;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\Cart\CartService;

readonly class OrderFromCartCreator
{
    public function __construct(
        private CartService $cartManager,
        private OrderCreator $creator,
    ) {
    }

    public function create(User $user): Order
    {
        $collection = new CreatableOrderItemCollection();

        foreach ($this->cartManager->getItems()->items as $item) {
            $collection->add(new CreatableOrderItemData(
                product: Product::find($item->id),
                quantity: $item->quantity,
            ));
        }

        $order = $this->creator->create($user, $collection);

        event(new OrderCreatedFromCart($order));

        return $order;
    }
}