<?php

namespace App\Models;

use App\Builders\CategoryQueryBuilder;
use App\Contracts\Seoble;
use App\Support\SeoBuilder;
use Database\Factories\CategoryFactory;
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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property BelongsToMany|Collection $features
 * @property-read int|null $features_count
 * @method static \Database\Factories\CategoryFactory factory($count = null, $state = [])
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
class Category extends Model implements Seoble
{
    use HasFactory, NodeTrait;

    protected $fillable = ['name', 'slug', 'title', 'parent_id'];

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class);
    }

    public function newEloquentBuilder($query): CategoryQueryBuilder
    {
        return new CategoryQueryBuilder($query);
    }

    protected static function newFactory(): CategoryFactory|\Illuminate\Database\Eloquent\Factories\Factory
    {
        return CategoryFactory::new();
    }

    public function seo(): SeoBuilder
    {
        return new SeoBuilder()
            ->title($this->title)
            ->description($this->title);
    }

    public function getRouteKeyName(): string
    {
        return 'full_path';
    }

    // Автогенерация slug
//    public static function boot()
//    {
//        parent::boot();
//
//        static::creating(function ($category) {
//            $category->slug = $category->slug ?? \Str::slug($category->name);
//        });
//    }
}
