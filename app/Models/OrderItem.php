<?php

namespace App\Models;

use App\Enum\OrderStatus;
use App\Builders\OrderItemQueryBuilder;
use App\Builders\OrderQueryBuilder;
use App\Builders\StockItemQueryBuilder;
use App\Support\Price;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property int $id
 * @property int $order_id
 * @property int $stock_item_id
 * @property int $price_base
 * @property int|null $price_discount
 * @property-read \App\Models\Order $order
 * @property-read mixed $price
 * @property-read \App\Models\StockItem $stockItem
 * @method static OrderItemQueryBuilder<static>|OrderItem descOrder()
 * @method static OrderItemQueryBuilder<static>|OrderItem isNew()
 * @method static OrderItemQueryBuilder<static>|OrderItem isPaid()
 * @method static OrderItemQueryBuilder<static>|OrderItem newModelQuery()
 * @method static OrderItemQueryBuilder<static>|OrderItem newQuery()
 * @method static OrderItemQueryBuilder<static>|OrderItem query()
 * @method static OrderItemQueryBuilder<static>|OrderItem whereId($value)
 * @method static OrderItemQueryBuilder<static>|OrderItem whereOrderId($value)
 * @method static OrderItemQueryBuilder<static>|OrderItem wherePriceBase($value)
 * @method static OrderItemQueryBuilder<static>|OrderItem wherePriceDiscount($value)
 * @method static OrderItemQueryBuilder<static>|OrderItem whereSeller(int $id)
 * @method static OrderItemQueryBuilder<static>|OrderItem whereStockItemId($value)
 * @method static OrderItemQueryBuilder<static>|OrderItem whereUser(int $id)
 * @method static OrderItemQueryBuilder<static>|OrderItem withOrder(bool $withUser = true)
 * @method static OrderItemQueryBuilder<static>|OrderItem withProduct(bool $withUser = true)
 * @method static OrderItemQueryBuilder<static>|OrderItem withStockItem()
 * @mixin \Eloquent
 */
class OrderItem extends Model
{
    public $timestamps = false;

    protected $table = 'orders_items';

    protected $fillable = ['order_id', 'stock_item_id', 'price_base', 'price_discount'];

    protected $casts = [
        'price_base' => 'int',
        'price_discount' => 'int',
    ];

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => new Price(
                $attributes['price_base'],
                $attributes['price_discount'],
            ),
        );
    }

    public function order(): BelongsTo|OrderQueryBuilder
    {
        return $this->belongsTo(Order::class);
    }

    public function stockItem(): BelongsTo|StockItemQueryBuilder
    {
        return $this->belongsTo(StockItem::class);
    }

    public function newEloquentBuilder($query): OrderItemQueryBuilder
    {
        return new OrderItemQueryBuilder($query);
    }
}
