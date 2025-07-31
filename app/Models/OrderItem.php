<?php

namespace App\Models;

use App\Enum\OrderStatus;
use App\QueryBuilders\OrderItemQueryBuilder;
use App\QueryBuilders\OrderQueryBuilder;
use App\QueryBuilders\StockItemQueryBuilder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $order_id
 * @property int $stock_item_id
 * @property int $price
 * @property-read \App\Models\Order $order
 * @property-read \App\Models\StockItem $stockItem
 * @method static OrderItemQueryBuilder<static>|OrderItem descOrder()
 * @method static OrderItemQueryBuilder<static>|OrderItem isNew()
 * @method static OrderItemQueryBuilder<static>|OrderItem isPaid()
 * @method static OrderItemQueryBuilder<static>|OrderItem newModelQuery()
 * @method static OrderItemQueryBuilder<static>|OrderItem newQuery()
 * @method static OrderItemQueryBuilder<static>|OrderItem query()
 * @method static OrderItemQueryBuilder<static>|OrderItem whereId($value)
 * @method static OrderItemQueryBuilder<static>|OrderItem whereOrderId($value)
 * @method static OrderItemQueryBuilder<static>|OrderItem wherePrice($value)
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

    protected $fillable = [
        'order_id',
        'stock_item_id',
        'price',
    ];

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
