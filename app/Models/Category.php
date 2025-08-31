<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use App\Builders\CategoryQueryBuilder;
use App\Contracts\Seoble;
use App\Observers\CategoryObserver;
use App\Support\SeoBuilder;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Kalnoy\Nestedset\NodeTrait;

/**
 *
 *
 * @property int $id
 * @property string $name
 * @property string $title
 * @property string $slug
 * @property string $full_path
 * @property int $parent_id
 * @property ?Category $parent
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property BelongsToMany|Collection $features
 * @property-read int|null $features_count
 * @method static CategoryFactory factory($count = null, $state = [])
 * @method static CategoryQueryBuilder<static>|Category newModelQuery()
 * @method static CategoryQueryBuilder<static>|Category newQuery()
 * @method static CategoryQueryBuilder<static>|Category query()
 * @method static CategoryQueryBuilder<static>|Category whereCreatedAt($value)
 * @method static CategoryQueryBuilder<static>|Category whereId($value)
 * @method static CategoryQueryBuilder<static>|Category whereName($value)
 * @method static CategoryQueryBuilder<static>|Category whereSlug($value)
 * @method static CategoryQueryBuilder<static>|Category whereUpdatedAt($value)
 * @method static CategoryQueryBuilder<static>|Category withFeatures()
 * @mixin \Eloquent
 */
#[ObservedBy([CategoryObserver::class])]
class Category extends Model implements Seoble
{
    use HasFactory, NodeTrait;

    protected $fillable = ['name', 'slug', 'title', 'parent_id'];

    protected $casts = [
        '_lft' => 'integer',
        '_rgt' => 'integer',
    ];

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class);
    }

    public function newEloquentBuilder($query): CategoryQueryBuilder
    {
        return new CategoryQueryBuilder($query);
    }

    protected static function newFactory(): CategoryFactory|Factory
    {
        return CategoryFactory::new();
    }

    public function seo(): SeoBuilder
    {
        return new SeoBuilder()
            ->title($this->title)
            ->description($this->title);
    }

}
