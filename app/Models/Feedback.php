<?php

namespace App\Models;

use App\Builders\FeedbackQueryBuilder;
use App\Builders\OrderItemQueryBuilder;
use App\Builders\ProductQueryBuilder;
use App\Builders\UserQueryBuilder;
use App\Observers\FeedbackObserver;
use Database\Factories\FeedbackFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
 * @method static FeedbackFactory factory($count = null, $state = [])
 * @method static FeedbackQueryBuilder query()
 */
#[ObservedBy([FeedbackObserver::class])]
class Feedback extends Model
{
    use HasFactory;

    public $table = 'feedbacks';

    protected $fillable = [
        'user_id',
        'order_item_id',
        'product_id',
        'seller_id',
        'is_positive',
        'comment',
        'created_at',
    ];

    protected $casts = [
        'is_positive' => 'boolean',
    ];

    public function isNegative(): bool
    {
        return !$this->is_positive;
    }

    public function isPositive(): bool
    {
        return $this->is_positive;
    }

    public function hasComment(): bool
    {
        return isset($this->comment);
    }

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

    public function newEloquentBuilder($query): FeedbackQueryBuilder
    {
        return new FeedbackQueryBuilder($query);
    }

    protected static function newFactory(): FeedbackFactory
    {
        return FeedbackFactory::new();
    }
}
