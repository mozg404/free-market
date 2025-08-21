<?php

namespace App\Models;

use App\Builders\OrderItemQueryBuilder;
use App\Builders\ProductQueryBuilder;
use App\Builders\UserQueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property-read User $user
 * @property int $order_item_id
 * @property-read OrderItem $orderItem
 * @property int $product_id
 * @property-read Product $product
 * @property int $seller_id
 * @property-read User $seller
 * @property bool $is_positive
 * @property ?string $comment
 */
class Feedback extends Model
{
    public $table = 'feedbacks';

    protected $fillable = [
        'user_id',
        'order_item_id',
        'product_id',
        'seller_id',
        'is_positive',
        'comment'
    ];

    protected $casts = [
        'is_positive' => 'boolean',
    ];

    // Отношения
    public function user(): BelongsTo|UserQueryBuilder
    {
        return $this->belongsTo(User::class);
    }

    public function orderItem(): BelongsTo|OrderItemQueryBuilder
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function product(): BelongsTo|ProductQueryBuilder
    {
        return $this->belongsTo(Product::class);
    }

    public function seller(): BelongsTo|UserQueryBuilder
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
