<?php

namespace App\Models;

use App\Contracts\Transactionable;
use App\Enum\OrderStatus;
use App\Builders\OrderItemQueryBuilder;
use App\Builders\OrderQueryBuilder;
use App\Builders\UserQueryBuilder;
use App\Support\Price;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

/**
 * /**
 *
 * @property int $id
 * @property int $user_id
 * @property int $amount
 * @property OrderStatus $status
 * @property \Illuminate\Support\Carbon|null $paid_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Collections\OrderItemCollection<int, \App\Models\OrderItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\User $user
 * @method static OrderQueryBuilder<static>|Order descOrder()
 * @method static OrderQueryBuilder<static>|Order isNew()
 * @method static OrderQueryBuilder<static>|Order isPaid()
 * @method static OrderQueryBuilder<static>|Order newModelQuery()
 * @method static OrderQueryBuilder<static>|Order newQuery()
 * @method static OrderQueryBuilder<static>|Order query()
 * @method static OrderQueryBuilder<static>|Order whereAmount($value)
 * @method static OrderQueryBuilder<static>|Order whereCreatedAt($value)
 * @method static OrderQueryBuilder<static>|Order whereId($value)
 * @method static OrderQueryBuilder<static>|Order wherePaidAt($value)
 * @method static OrderQueryBuilder<static>|Order whereStatus($value)
 * @method static OrderQueryBuilder<static>|Order whereUpdatedAt($value)
 * @method static OrderQueryBuilder<static>|Order whereUser(int $id)
 * @method static OrderQueryBuilder<static>|Order whereUserId($value)
 * @method static OrderQueryBuilder<static>|Order withItems()
 * @method static OrderQueryBuilder<static>|Order withItemsCount()
 * @method static OrderQueryBuilder<static>|Order withProductSellers()
 * @method static OrderQueryBuilder<static>|Order withProducts()
 * @method static OrderQueryBuilder<static>|Order withStockItems()
 * @method static OrderQueryBuilder<static>|Order withUser()
 * @mixin \Eloquent
 */
class Order extends Model implements Transactionable
{
    protected $casts = [
        'status' => OrderStatus::class,
        'paid_at' => 'datetime',
    ];

    protected $fillable = [
        'user_id',
        'status',
        'amount',
    ];

    public static function new(User $user, int $amount): static
    {
        return static::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'status' => OrderStatus::NEW,
        ]);
    }

    public function isNew(): bool
    {
        return $this->status === OrderStatus::NEW;
    }

    public function isPaid(): bool
    {
        return $this->status === OrderStatus::PAID;
    }

    public function paid(): void
    {
        DB::transaction(function () {
            // Меняем статус заказа
            $this->status = OrderStatus::PAID;
            $this->paid_at = Carbon::now();
            $this->save();

            // Меняем статус позиций на складе на оплачено
            foreach ($this->items as $item) {
                $item->stockItem->sold($this->user);
            }
        });
    }

    public function getTransactionableType(): string
    {
        return $this::class;
    }

    public function getTransactionableId(): int
    {
        return $this->id;
    }

    public function user(): BelongsTo|UserQueryBuilder
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany|OrderItemQueryBuilder
    {
        return $this->hasMany(OrderItem::class);
    }

    public function newEloquentBuilder($query): OrderQueryBuilder
    {
        return new OrderQueryBuilder($query);
    }
}
