<?php

namespace App\Models;

use Database\Factories\ProductFeatureValueFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * 
 *
 * @property int $id
 * @property int $product_id
 * @property int $feature_id
 * @property Feature $feature
 * @property Product $product
 * @property string $value
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductFeatureValue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductFeatureValue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductFeatureValue query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductFeatureValue whereFeatureId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductFeatureValue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductFeatureValue whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductFeatureValue whereValue($value)
 * @mixin \Eloquent
 */
class ProductFeatureValue extends Pivot
{
    use HasFactory;

    public const TABLE = 'product_feature_values';
    protected $table = self::TABLE;
    public $timestamps = false;
    protected $fillable = ['value'];

    public function feature(): BelongsTo
    {
        return $this->belongsTo(Feature::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    protected static function newFactory(): ProductFeatureValueFactory
    {
        return ProductFeatureValueFactory::new();
    }
}
