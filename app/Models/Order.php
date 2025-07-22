<?php

namespace App\Models;

use App\Enum\OrderStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * /**
 * @property int $id
 * @property int $user_id
 * @property int $total_price
 * @property OrderStatus $status
 * @property User $user
 * @property Collection $items
 * @property Carbon|null $paid_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
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

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
