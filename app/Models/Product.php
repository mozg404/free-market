<?php

namespace App\Models;

use App\QueryBuilders\ProductQueryBuilder;
use App\Support\Filepond\Image;
use App\Support\Phone;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $shop_id
 * @property string $name
 * @property string $slug
 * @property boolean $is_available
 * @property float $price
 * @property float $price_discount
 * @property Image|null $image
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
        'price',
        'price_discount',
        'is_available',
        'image',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'float',
            'price_discount' => 'float',
            'is_available' => 'boolean',
        ];
    }

    public static function new(Shop|int $shop, string $name, float $price, Image $image, bool $isAvailable, float $priceDiscount = null): static
    {
        if (is_a($shop, Shop::class)) {
            $shop = $shop->id;
        }

        if ($image->isTemporary()) {
            $image->publish();
        }

        $product = new Product();
        $product->shop_id = $shop;
        $product->name = $name;
        $product->price = $price;
        $product->price_discount = $priceDiscount;
        $product->is_available = $isAvailable;
        $product->image = $image;
        $product->save();

        return $product;
    }

    public function edit(string $name, float $price, Image $image, bool $isAvailable, float $priceDiscount = null): static
    {
        if ($image->isTemporary()) {
            $image->publish();
        }

        $this->name = $name;
        $this->price = $price;
        $this->price_discount = $priceDiscount;
        $this->is_available = $isAvailable;
        $this->image = $image;
        $this->save();

        return $this;
    }

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => is_string($value) ? Image::from($value) : $value,
            set: fn (Phone|string|null $value) => is_string($value) ? Image::from($value) : $value,
        );
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
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
