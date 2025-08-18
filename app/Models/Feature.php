<?php

namespace App\Models;

use App\Builders\FeatureQueryBuilder;
use App\Collections\FeatureCollection;
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
 * @property-read Category $category
 * @method static FeatureFactory factory($count = null, $state = [])
 * @method static FeatureQueryBuilder<static>|Feature newModelQuery()
 * @method static FeatureQueryBuilder<static>|Feature newQuery()
 * @method static FeatureQueryBuilder<static>|Feature query()
 * @method static FeatureQueryBuilder<static>|Feature whereCategoryId($value)
 * @method static FeatureQueryBuilder<static>|Feature whereCreatedAt($value)
 * @method static FeatureQueryBuilder<static>|Feature whereId($value)
 * @method static FeatureQueryBuilder<static>|Feature whereIsRequired($value)
 * @method static FeatureQueryBuilder<static>|Feature whereName($value)
 * @method static FeatureQueryBuilder<static>|Feature whereOptions($value)
 * @method static FeatureQueryBuilder<static>|Feature whereType($value)
 * @method static FeatureQueryBuilder<static>|Feature whereUpdatedAt($value)
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

    public function newCollection(array $models = []): FeatureCollection
    {
        return new FeatureCollection($models);
    }

    public function newEloquentBuilder($query): FeatureQueryBuilder
    {
        return new FeatureQueryBuilder($query);
    }

    protected static function newFactory(): FeatureFactory|\Illuminate\Database\Eloquent\Factories\Factory
    {
        return FeatureFactory::new();
    }
}
