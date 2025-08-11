<?php

namespace App\Collections;

use App\Data\Orders\CreatableOrderData;
use App\Data\Orders\CreatableOrderItemData;
use App\Support\Price;
use Illuminate\Support\Collection;

/**
 * @property CreatableOrderItemData[] $items
 */
class CreatableOrderItemCollection extends Collection
{
    public function getTotalPrice(): Price
    {
        $price = new Price(0);

        $this->each(function (CreatableOrderItemData $item) use (&$price) {
            $price = $price->sumWith($item->product->price)->multiply($item->quantity);
        });

        return $price;
    }
}