<?php

namespace App\Models;

use App\Collections\ArticleCollection;
use App\Collections\ProductCollection;
use App\QueryBuilders\ProductQueryBuilder;
use App\Support\Filepond\Image;
use App\Support\Filepond\ImageStub;
use App\Support\Phone;
use App\Support\Price;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property Product $shop_id
 * @property string $name
 * @property string $slug
 * @property boolean $is_available
 * @property Price $price
 * @property Product $price_base
 * @property Product $price_discount
 * @property Image|ImageStub|null $preview_image
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\Shop $shop
 * @method static \Database\Factories\ProductFactory factory($count = null, $state = [])
 * @method static ProductQueryBuilder<static>|Product newModelQuery()
 * @method static ProductQueryBuilder<static>|Product newQuery()
 * @method static ProductQueryBuilder<static>|Product query()
 * @method static ProductQueryBuilder<static>|Product whereCreatedAt($value)
 * @method static ProductQueryBuilder<static>|Product whereId($value)
 * @method static ProductQueryBuilder<static>|Product whereImage($value)
 * @method static ProductQueryBuilder<static>|Product whereName($value)
 * @method static ProductQueryBuilder<static>|Product wherePrice($value)
 * @method static ProductQueryBuilder<static>|Product whereShopId($value)
 * @method static ProductQueryBuilder<static>|Product whereSlug($value)
 * @method static ProductQueryBuilder<static>|Product whereUpdatedAt($value)
 * @method static ProductQueryBuilder<static>|Product withShop()
 * @mixin \Eloquent
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'price_base',
        'price_discount',
        'is_available',
        'image',
    ];

    protected function casts(): array
    {
        return [
            'price_base' => 'int',
            'price_discount' => 'int',
            'is_available' => 'boolean',
        ];
    }

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => new Price(
                $attributes['price_base'],
                $attributes['price_discount'],
            ),
        );
    }

    protected function previewImage(): Attribute
    {
        return Attribute::make(
            get: static function (string|null $id) {
                if (is_string($id) && Image::exists($id)) {
                    return Image::from($id);
                }

                return new ImageStub();
            },
            set: static function (Image|ImageStub|string|null $id) {
                if (is_a($id, Image::class)) {
                    return $id;
                }

                if (is_string($id) && Image::exists($id)) {
                    return Image::from($id);
                }

                return new ImageStub();
            },
        );
    }

    public function toArray(): array
    {
        $array = parent::toArray();
        $array['price'] = $this->price->toArray();

        return $array;
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function newCollection(array $models = []): ProductCollection
    {
        return new ProductCollection($models);
    }

    public function newEloquentBuilder($query): ProductQueryBuilder
    {
        return new ProductQueryBuilder($query);
    }

    protected static function newFactory(): ProductFactory|\Illuminate\Database\Eloquent\Factories\Factory
    {
        return ProductFactory::new();
    }
}
