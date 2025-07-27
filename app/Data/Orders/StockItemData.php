<?php

namespace App\Data\Orders;

class StockItemData
{
    public function __construct(
        public int $id,
        public int $price,
        public int $quantity,
    )
    {}
}