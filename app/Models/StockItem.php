<?php

namespace App\Models;

use App\Enum\StockItemStatus;
use App\QueryBuilders\StockItemQueryBuilder;
use Database\Factories\StockItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $product_id
 * @property StockItemStatus $status
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockItem whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockItem whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockItem whereUpdatedAt($value)
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

    public function isSold(): bool
    {
        return $this->status === StockItemStatus::SOLD;
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