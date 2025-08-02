<?php

namespace App\Models;

use App\Builders\CategoryQueryBuilder;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static CategoryQueryBuilder<static>|Category newModelQuery()
 * @method static CategoryQueryBuilder<static>|Category newQuery()
 * @method static CategoryQueryBuilder<static>|Category query()
 * @method static CategoryQueryBuilder<static>|Category whereCreatedAt($value)
 * @method static CategoryQueryBuilder<static>|Category whereId($value)
 * @method static CategoryQueryBuilder<static>|Category whereName($value)
 * @method static CategoryQueryBuilder<static>|Category whereSlug($value)
 * @method static CategoryQueryBuilder<static>|Category whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public function features(): HasMany
    {
        return $this->hasMany(Feature::class);
    }

    public function newEloquentBuilder($query): CategoryQueryBuilder
    {
        return new CategoryQueryBuilder($query);
    }

    protected static function newFactory(): CategoryFactory|\Illuminate\Database\Eloquent\Factories\Factory
    {
        return CategoryFactory::new();
    }

    // Автогенерация slug
    public static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug = $category->slug ?? \Str::slug($category->name);
        });
    }
}
