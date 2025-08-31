<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use App\Builders\OrderItemQueryBuilder;
use App\Builders\ProductQueryBuilder;
use App\Enum\StockItemStatus;
use App\Builders\StockItemQueryBuilder;
use Database\Factories\StockItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $product_id
 * @property StockItemStatus $status
 * @property string $content
 * @property int|null $order_item_id
 * @property OrderItem|null $orderItem
 * @property Carbon|null $pinned_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Product $product
 * @method static StockItemFactory factory($count = null, $state = [])
 * @method static StockItemQueryBuilder<static>|StockItem forPinnedUser((User|int) $id)
 * @method static StockItemQueryBuilder<static>|StockItem forProduct((Product|int) $id)
 * @method static StockItemQueryBuilder<static>|StockItem forUser((User|int) $id)
 * @method static StockItemQueryBuilder<static>|StockItem isAvailable()
 * @method static StockItemQueryBuilder<static>|StockItem isReserved()
 * @method static StockItemQueryBuilder<static>|StockItem isSold()
 * @method static StockItemQueryBuilder<static>|StockItem newModelQuery()
 * @method static StockItemQueryBuilder<static>|StockItem newQuery()
 * @method static StockItemQueryBuilder<static>|StockItem query()
 * @method static StockItemQueryBuilder<static>|StockItem whereContent($value)
 * @method static StockItemQueryBuilder<static>|StockItem whereCreatedAt($value)
 * @method static StockItemQueryBuilder<static>|StockItem whereId($value)
 * @method static StockItemQueryBuilder<static>|StockItem wherePinnedAt($value)
 * @method static StockItemQueryBuilder<static>|StockItem wherePinnedUserId($value)
 * @method static StockItemQueryBuilder<static>|StockItem whereProductId($value)
 * @method static StockItemQueryBuilder<static>|StockItem whereStatus($value)
 * @method static StockItemQueryBuilder<static>|StockItem whereUpdatedAt($value)
 * @method static StockItemQueryBuilder<static>|StockItem withPinnedUser()
 * @mixin \Eloquent
 */
class StockItem extends Model
{
    use HasFactory;

    protected $table = 'stock_items';

    protected $fillable = ['product_id', 'order_item_id', 'status', 'content'];

    protected $hidden = ['content'];

    protected function casts(): array
    {
        return [
            'status' => StockItemStatus::class,
            'pinned_at' => 'datetime',
        ];
    }

    /**
     * Помечает позицию как доступную для продажи
     * @return void
     */
    public function markAsAvailable(): void
    {
        $this->status = StockItemStatus::AVAILABLE;
        $this->order_id = null;
        $this->save();
    }

    public function isAvailable(): bool
    {
        return $this->status === StockItemStatus::AVAILABLE;
    }

    public function isReserved(): bool
    {
        return $this->status === StockItemStatus::RESERVED;
    }

    /**
     * Создает новую позицию
     * @param Product $product
     * @param string $content
     * @return StockItem
     */
    public static function new(Product $product, string $content): StockItem
    {
        return $product->stockItems()->create([
            'content' => $content,
            'status' => StockItemStatus::AVAILABLE,
        ]);
    }

    /**
     * Изменяет содержимое позиции
     * @param string $content
     * @return void
     */
    public function edit(string $content): void
    {
        $this->content = $content;
        $this->save();
    }

    public function product(): BelongsTo|ProductQueryBuilder
    {
        return $this->belongsTo(Product::class);
    }

    public function orderItem(): BelongsTo|OrderItemQueryBuilder|null
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function newEloquentBuilder($query): StockItemQueryBuilder
    {
        return new StockItemQueryBuilder($query);
    }

    protected static function newFactory(): StockItemFactory|Factory
    {
        return StockItemFactory::new();
    }
}