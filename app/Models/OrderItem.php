<?php

namespace App\Models;

use App\Builders\OrderItemQueryBuilder;
use App\Builders\OrderQueryBuilder;
use App\Builders\StockItemQueryBuilder;
use App\Collections\OrderItemCollection;
use App\Contracts\Transactionable;
use App\Support\Price;
use Database\Factories\OrderItemFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property int $id
 * @property int $order_id
 * @property int $stock_item_id
 * @property int $current_price
 * @property int $base_price
 * @property-read \App\Models\Order $order
 * @property Price $price
 * @property-read \App\Models\StockItem $stockItem
 * @method static OrderItemCollection<int, static> all($columns = ['*'])
 * @method static OrderItemQueryBuilder<static>|OrderItem descOrder()
 * @method static \Database\Factories\OrderItemFactory factory($count = null, $state = [])
 * @method static OrderItemQueryBuilder<static>|OrderItem for(\App\Models\Order|\App\Models\User $model)
 * @method static OrderItemQueryBuilder<static>|OrderItem forOrder(\App\Models\Order|int $order)
 * @method static OrderItemQueryBuilder<static>|OrderItem forUser(\App\Models\User|int $user)
 * @method static OrderItemCollection<int, static> get($columns = ['*'])
 * @method static OrderItemQueryBuilder<static>|OrderItem isNew()
 * @method static OrderItemQueryBuilder<static>|OrderItem isPaid()
 * @method static OrderItemQueryBuilder<static>|OrderItem newModelQuery()
 * @method static OrderItemQueryBuilder<static>|OrderItem newQuery()
 * @method static OrderItemQueryBuilder<static>|OrderItem query()
 * @method static OrderItemQueryBuilder<static>|OrderItem whereBasePrice($value)
 * @method static OrderItemQueryBuilder<static>|OrderItem whereCurrentPrice($value)
 * @method static OrderItemQueryBuilder<static>|OrderItem whereId($value)
 * @method static OrderItemQueryBuilder<static>|OrderItem whereOrderId($value)
 * @method static OrderItemQueryBuilder<static>|OrderItem whereSeller(int $id)
 * @method static OrderItemQueryBuilder<static>|OrderItem whereStockItemId($value)
 * @method static OrderItemQueryBuilder<static>|OrderItem withOrder()
 * @method static OrderItemQueryBuilder<static>|OrderItem withOrderUser()
 * @method static OrderItemQueryBuilder<static>|OrderItem withProduct()
 * @method static OrderItemQueryBuilder<static>|OrderItem withProductUser()
 * @method static OrderItemQueryBuilder<static>|OrderItem withStockItem()
 * @mixin \Eloquent
 */
class OrderItem extends Model implements Transactionable
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'orders_items';

    protected $fillable = ['order_id', 'stock_item_id', 'base_price', 'current_price'];

    protected $casts = [
        'current_price' => 'int',
        'base_price' => 'int',
    ];

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn() => new Price(
                $this->base_price,
                $this->current_price
            ),
            set: fn(Price $price) => [
                'base_price' => $price->getBasePrice(),
                'current_price' => $price->getCurrentPrice()
            ]
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

    public function getTransactionableType(): string
    {
        return $this::class;
    }

    public function getTransactionableId(): int
    {
        return $this->id;
    }

    public function newCollection(array $models = []): OrderItemCollection
    {
        return new OrderItemCollection($models);
    }

    public function newEloquentBuilder($query): OrderItemQueryBuilder
    {
        return new OrderItemQueryBuilder($query);
    }

    protected static function newFactory(): OrderItemFactory|\Illuminate\Database\Eloquent\Factories\Factory
    {
        return OrderItemFactory::new();
    }
}
