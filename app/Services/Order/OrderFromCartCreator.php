<?php

namespace App\Services\Order;

use App\Collections\CreatableOrderItemCollection;
use App\Data\Orders\CreatableOrderItemData;
use App\Events\OrderCreatedFromCart;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockItem;
use App\Models\User;
use App\Services\Cart\CartManager;
use App\Services\Product\ProductManager;

readonly class OrderFromCartCreator
{
    public function __construct(
        private CartManager $cartManager,
        private OrderCreator $creator,
    ) {
    }

    /**
     * Создает новый заказ из данных корзины
     * @param User $user
     * @return Order
     */
    public function create(User $user): Order
    {
        $collection = new CreatableOrderItemCollection();

        foreach ($this->cartManager->all()->items as $item) {
            $collection->add(new CreatableOrderItemData(
                product: Product::find($item->product->id),
                quantity: $item->quantity,
            ));
        }

        $order = $this->creator->create($user, $collection);

        event(new OrderCreatedFromCart($order));

        return $order;
    }
}