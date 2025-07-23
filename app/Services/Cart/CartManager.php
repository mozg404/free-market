<?php

namespace App\Services\Cart;

use App\Data\Cart\CartData;
use App\Data\Cart\CartItemData;
use App\Models\Product;

class CartManager
{
    private Cart $cart;

    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    public function add(Product $product)
    {
        $this->cart->add($product->id);
    }

    public function remove(Product $product)
    {
        $this->cart->remove($product->id);
    }

    public function delete(Product $product)
    {
        $this->cart->delete($product->id);
    }

    public function clean()
    {
        $this->cart->clean();
    }

    /**
     * Возвращает данные корзины
     * @return CartData
     */
    public function all(): CartData
    {
        $cart = $this->cart->all();
        $items = [];

        // Получаем перечень товаров с ценами
        $products = Product::query()
            ->whereIds(array_keys($cart))
            ->get();

        // Перемножаем товары на количество и суммируем
        foreach ($cart as $id => $cartItems) {
            $items[$id] = CartItemData::from([
                'product' => $products->find($id),
                'quantity' => $cartItems['quantity'],
            ]);
        }

        return new CartData($items);
    }
}