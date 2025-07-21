<?php

namespace App\Services\Cart;

use App\Models\Product;
use Illuminate\Contracts\Session\Session;

class SessionCart implements Cart
{
    public const SESSION_KEY = 'cart';

    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function add(int $productId): void
    {
        $cart = $this->all();
        $cart[$productId] = [
            'product_id' => $productId,
            'quantity' => ($cart[$productId]['quantity'] ?? 0) + 1,
        ];
        $this->session->put(self::SESSION_KEY, $cart);
    }

    public function remove(int $productId): void
    {
        $cart = $this->all();

        if (isset($cart[$productId])) {
            if ($cart[$productId]['quantity'] > 1) {
                $cart[$productId]['quantity']--;
                $this->session->put(self::SESSION_KEY, $cart);
            } else {
                $this->delete($productId);
            }
        }
    }

    public function delete(int $productId): void
    {
        $this->session->forget(self::SESSION_KEY.'.'.$productId);
    }

    public function clean(): void
    {
        $this->session->forget(self::SESSION_KEY);
    }

    public function all(): array
    {
        return $this->session->get(self::SESSION_KEY, []);
    }
}