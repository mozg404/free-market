<?php

namespace App\Models;

use App\Contracts\Transactionable;
use App\Enum\StockItemStatus;
use App\Builders\StockItemQueryBuilder;
use Database\Factories\StockItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property int $id
 * @property int $product_id
 * @property StockItemStatus $status
 * @property string $content
 * @property int|null $pinned_user_id
 * @property \Illuminate\Support\Carbon|null $pinned_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $pinnedUser
 * @property-read \App\Models\Product $product
 * @method static \Database\Factories\StockItemFactory factory($count = null, $state = [])
 * @method static StockItemQueryBuilder<static>|StockItem forPinnedUser(\App\Models\User|int $id)
 * @method static StockItemQueryBuilder<static>|StockItem forProduct(\App\Models\Product|int $id)
 * @method static StockItemQueryBuilder<static>|StockItem forUser(\App\Models\User|int $id)
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
class StockItem extends Model implements Transactionable
{
    use HasFactory;

    protected $table = 'stock_items';

    protected $fillable = ['product_id', 'status', 'content'];

    protected $hidden = ['content'];

    protected function casts(): array
    {
        return [
            'status' => StockItemStatus::class,
            'pinned_at' => 'datetime',
        ];
    }

    /**
     * Переводит статус к "Доступно"
     * @return void
     */
    public function toAvailable(): void
    {
        $this->status = StockItemStatus::AVAILABLE;
        $this->pinned_user_id = null;
        $this->pinned_at = null;
        $this->save();
    }

    public function isAvailable(): bool
    {
        return $this->status === StockItemStatus::AVAILABLE;
    }

    /**
     * Резервирует позицию за пользователем
     * @param User $user
     * @return void
     */
    public function reserve(User $user): void
    {
        $this->status = StockItemStatus::RESERVED;
        $this->pinned_user_id = $user->id;
        $this->pinned_at = now();
        $this->save();
    }

    public function isReserved(): bool
    {
        return $this->status === StockItemStatus::RESERVED;
    }

    /**
     * Помечает позицию, как проданную
     * @param User $user
     * @return void
     */
    public function sold(User $user): void
    {
        $this->status = StockItemStatus::SOLD;
        $this->pinned_user_id = $user->id;
        $this->pinned_at = now();
        $this->save();
    }

    public function isSold(): bool
    {
        return $this->status === StockItemStatus::SOLD;
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

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function pinnedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pinned_user_id');
    }

    public function getTransactionableType(): string
    {
        return $this::class;
    }

    public function getTransactionableId(): int
    {
        return $this->id;
    }

    public function newEloquentBuilder($query): StockItemQueryBuilder
    {
        return new StockItemQueryBuilder($query);
    }

    protected static function newFactory(): StockItemFactory|\Illuminate\Database\Eloquent\Factories\Factory
    {
        return StockItemFactory::new();
    }
}