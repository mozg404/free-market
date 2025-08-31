<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Builders\OrderItemQueryBuilder;
use App\Builders\OrderQueryBuilder;
use App\Builders\ProductQueryBuilder;
use App\Builders\StockItemQueryBuilder;
use App\Builders\UserQueryBuilder;
use App\Collections\OrderItemCollection;
use App\Contracts\Transactionable;
use App\Support\Price;
use Database\Factories\OrderItemFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

/**
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property int $stock_item_id
 * @property int $current_price
 * @property int $base_price
 * @property Price $price
 * @property-read Order $order
 * @property-read StockItem $stockItem
 * @property-read Product $product
 * @property-read User $buyer
 * @property-read User $seller
 * @property-read Feedback $feedback
 * @method static OrderItemCollection<int, static> all($columns = ['*'])
 * @method static OrderItemFactory factory($count = null, $state = [])
 * @method static OrderItemCollection<int, static> get($columns = ['*'])
 * @method static OrderItemQueryBuilder<static>|OrderItem newModelQuery()
 * @method static OrderItemQueryBuilder<static>|OrderItem newQuery()
 * @method static OrderItemQueryBuilder<static>|OrderItem query()
 */
class OrderItem extends Model implements Transactionable
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['order_id', 'product_id', 'stock_item_id', 'base_price', 'current_price'];

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

    public function seller(): HasOneThrough|UserQueryBuilder
    {
        return $this->hasOneThrough(
            User::class,
            Product::class,
            'id',
            'id',
            'product_id',
            'user_id'
        );
    }

    public function buyer(): HasOneThrough|UserQueryBuilder
    {
        return $this->hasOneThrough(
            User::class,
            Order::class,
            'id',
            'id',
            'order_id',
            'user_id'
        );
    }

    public function feedback(): HasOne
    {
        return $this->hasOne(Feedback::class);
    }

    public function order(): BelongsTo|OrderQueryBuilder
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo|ProductQueryBuilder
    {
        return $this->belongsTo(Product::class);
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

    protected static function newFactory(): OrderItemFactory|Factory
    {
        return OrderItemFactory::new();
    }
}
