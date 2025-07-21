<?php

namespace App\Support;

use Illuminate\Contracts\Support\Arrayable;

class Price implements Arrayable
{
    public function __construct(
        public int $base = 0,
        public int|null $discount = null
    ){
        if (isset($discount) && $discount >= $base) {
            throw new \InvalidArgumentException('Discount not must be greater than base price');
        }
    }

    /**
     * Возвращает TRUE, если товар со скидкой
     * @return bool
     */
    public function isDiscount(): bool
    {
        return !empty($this->discount);
    }

    /**
     * Возвращает текущую цену (с учетом скидки, если есть)
     * @return int
     */
    public function getCurrent(): int
    {
        return $this->isDiscount() ? $this->discount : $this->base;
    }

    /**
     * Рассчитывает процент скидки
     * @return int
     */
    public function calculateDiscountPercent(): int
    {
        return floor($this->discount / $this->base * 100);
    }

    /**
     * Рассчитывает текущую цену исходя из указанного количества
     * @param int $quantity
     * @return int
     */
    public function calculatePriceByQuantity(int $quantity): int
    {
        return $this->getCurrent() * $quantity;
    }

    public function toArray(): array
    {
        return [
            'current' => $this->getCurrent(),
            'base' => $this->base,
            'discount' => $this->discount,
            'discountPercent' => $this->calculateDiscountPercent(),
            'isDiscount' => $this->isDiscount(),
        ];
    }
}