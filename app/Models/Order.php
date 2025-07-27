<?php

namespace App\Models;

use App\Enum\OrderStatus;
use App\QueryBuilders\OrderQueryBuilder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * /**
 *
 * @property int $id
 * @property int $user_id
 * @property int $total_price
 * @property OrderStatus $status
 * @property \Illuminate\Support\Carbon|null $paid_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection<int, \App\Models\OrderItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\User $user
 * @method static OrderQueryBuilder<static>|Order newModelQuery()
 * @method static OrderQueryBuilder<static>|Order newQuery()
 * @method static OrderQueryBuilder<static>|Order query()
 * @method static OrderQueryBuilder<static>|Order whereCreatedAt($value)
 * @method static OrderQueryBuilder<static>|Order whereId($value)
 * @method static OrderQueryBuilder<static>|Order wherePaidAt($value)
 * @method static OrderQueryBuilder<static>|Order whereStatus($value)
 * @method static OrderQueryBuilder<static>|Order whereTotalPrice($value)
 * @method static OrderQueryBuilder<static>|Order whereUpdatedAt($value)
 * @method static OrderQueryBuilder<static>|Order whereUserId($value)
 * @mixin \Eloquent
 */
class Order extends Model
{
    protected $casts = [
        'status' => OrderStatus::class,
        'paid_at' => 'datetime',
    ];

    protected $fillable = [
        'user_id',
        'status',
        'total_price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function newEloquentBuilder($query): OrderQueryBuilder
    {
        return new OrderQueryBuilder($query);
    }
}
