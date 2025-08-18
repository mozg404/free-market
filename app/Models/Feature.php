<?php

namespace App\Models;

use App\Enum\FeatureType;
use Database\Factories\FeatureFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * 
 *
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property FeatureType $type
 * @property array<array-key, mixed>|null $options
 * @property bool $is_required
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category $category
 * @method static \Database\Factories\FeatureFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feature newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feature newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feature query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feature whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feature whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feature whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feature whereIsRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feature whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feature whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feature whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feature whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Feature extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'key',
        'type',
        'options',
        'is_required'
    ];

    protected $casts = [
        'type'  => FeatureType::class,
        'options' => 'array',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    protected static function newFactory(): FeatureFactory|\Illuminate\Database\Eloquent\Factories\Factory
    {
        return FeatureFactory::new();
    }
}
