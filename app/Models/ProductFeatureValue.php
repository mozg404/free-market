<?php

namespace App\Models;

use Database\Factories\ProductFeatureValueFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * 
 *
 * @property int $id
 * @property int $product_id
 * @property int $feature_id
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
    public const TABLE = 'product_feature_values';
    protected $table = self::TABLE;
    public $timestamps = false;
    protected $fillable = ['value'];
}
