<?php

namespace App\Models;

use App\Enum\OrderStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * /**
 * @property int $id
 * @property int $order_id
 * @property Order $order
 * @property int $product_id
 * @property Product $product
 * @property int $quantity
 * @property int $price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class OrderItem extends Model
{
    protected $table = 'orders_items';
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
