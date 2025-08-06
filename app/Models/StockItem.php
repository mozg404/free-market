<?php

namespace App\Models;

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
 * @property int|null $buyer_id
 * @property string|null $sold_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $ProductCard
 * @method static \Database\Factories\StockItemFactory factory($count = null, $state = [])
 * @method static StockItemQueryBuilder<static>|StockItem isAvailable()
 * @method static StockItemQueryBuilder<static>|StockItem isReserved()
 * @method static StockItemQueryBuilder<static>|StockItem isSold()
 * @method static StockItemQueryBuilder<static>|StockItem newModelQuery()
 * @method static StockItemQueryBuilder<static>|StockItem newQuery()
 * @method static StockItemQueryBuilder<static>|StockItem query()
 * @method static StockItemQueryBuilder<static>|StockItem whereBuyerId($value)
 * @method static StockItemQueryBuilder<static>|StockItem whereContent($value)
 * @method static StockItemQueryBuilder<static>|StockItem whereCreatedAt($value)
 * @method static StockItemQueryBuilder<static>|StockItem whereId($value)
 * @method static StockItemQueryBuilder<static>|StockItem whereProductId($value)
 * @method static StockItemQueryBuilder<static>|StockItem whereSoldAt($value)
 * @method static StockItemQueryBuilder<static>|StockItem whereStatus($value)
 * @method static StockItemQueryBuilder<static>|StockItem whereUpdatedAt($value)
 * @method static StockItemQueryBuilder<static>|StockItem whereUser(int $id)
 * @mixin \Eloquent
 */
class StockItem extends Model
{
    use HasFactory;

    protected $table = 'stock_items';

    protected $fillable = ['product_id', 'status', 'content'];

    protected $hidden = ['content'];

    protected function casts(): array
    {
        return [
            'status' => StockItemStatus::class,
        ];
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
     * Резервирует позицию
     * @return void
     */
    public function reserve(): void
    {
        $this->status = StockItemStatus::RESERVED;
        $this->save();
    }

    public function isSold(): bool
    {
        return $this->status === StockItemStatus::SOLD;
    }

    /**
     * Помечает позицию, как проданную
     * @param User $user
     * @return void
     */
    public function sold(User $user): void
    {
        $this->status = StockItemStatus::SOLD;
        $this->buyer_id = $user->id;
        $this->save();
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
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