<?php

namespace App\Services\Cart;

use App\Models\Product;
use Illuminate\Session\Store as Session;

interface Cart
{
    /**
     * Добавляет товар в корзину в кол-ве 1шт
     *
     * @param int $productId
     * @return void
     */
    public function add(int $productId): void;

    /**
     * Убирает товар из корзины в кол-ве 1шт
     *
     * @param int $productId
     * @return void
     */
    public function remove(int $productId): void;

    /**
     * Полностью убирает определенный товар из корзины
     *
     * @param int $productId
     * @return void
     */
    public function delete(int $productId): void;

    /**
     * Очищает корзину
     *
     * @return void
     */
    public function clean(): void;

    /**
     * Возвращает весь перечень товаров из корзины
     *
     * @return array
     */
    public function all(): array;
}