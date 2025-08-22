<?php

namespace App\Models;

use App\Builders\FeedbackQueryBuilder;
use App\Builders\ProductQueryBuilder;
use App\Builders\StockItemQueryBuilder;
use App\Casts\ImageCast;
use App\Collections\FeatureCollection;
use App\Collections\ProductCollection;
use App\Contracts\Seoble;
use App\Data\Products\ProductEditableData;
use App\Enum\ProductStatus;
use App\Support\Image;
use App\Support\Price;
use App\Support\SeoBuilder;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Mews\Purifier\Casts\CleanHtmlInput;

/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property int $current_price
 * @property int $base_price
 * @property ProductStatus $status
 * @property ?Image $image
 * @property ?string|null $image_url
 * @property array|null $description
 * @property array|null $instruction
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $category_id
 * @property int $positive_feedbacks_count
 * @property int $negative_feedbacks_count
 * @property float $rating
 * @property-read \App\Models\Category|null $category
 * @property-read \App\Models\ProductFeatureValue|null $pivot
 * @property-read FeatureCollection $features
 * @property-read int|null $features_count
 * @property Price $price
 * @property-read Collection<int, \App\Models\StockItem> $stockItems
 * @property-read Collection<int,Feedback> $feedbacks
 * @property-read int|null $stock_items_count
 * @property-read \App\Models\User $user
 * @method static ProductCollection<int, static> all($columns = ['*'])
 * @method static ProductQueryBuilder<static>|Product descOrder()
 * @method static \Database\Factories\ProductFactory factory($count = null, $state = [])
 * @method static ProductQueryBuilder<static>|Product filterFromArray(array $data)
 * @method static ProductQueryBuilder<static>|Product for(\App\Models\User|\App\Models\Category $object)
 * @method static ProductQueryBuilder<static>|Product forCategory(\App\Models\Category|int $category)
 * @method static ProductQueryBuilder<static>|Product forListing()
 * @method static ProductQueryBuilder<static>|Product forUser(\App\Models\User|int $user)
 * @method static ProductCollection<int, static> get($columns = ['*'])
 * @method static ProductQueryBuilder<static>|Product hasAvailableStock()
 * @method static ProductQueryBuilder<static>|Product hasStockItems()
 * @method static ProductQueryBuilder<static>|Product isActive()
 * @method static ProductQueryBuilder<static>|Product isDraft()
 * @method static ProductQueryBuilder<static>|Product isPaused()
 * @method static ProductQueryBuilder<static>|Product isPublished()
 * @method static ProductQueryBuilder<static>|Product newModelQuery()
 * @method static ProductQueryBuilder<static>|Product newQuery()
 * @method static ProductQueryBuilder<static>|Product onlyDiscounted()
 * @method static ProductQueryBuilder<static>|Product orderByActualPrice(string $direction = 'asc')
 * @method static ProductQueryBuilder<static>|Product query()
 * @method static ProductQueryBuilder<static>|Product searchByName(string $search)
 * @method static ProductQueryBuilder<static>|Product whereBasePrice($value)
 * @method static ProductQueryBuilder<static>|Product whereCategoryId($value)
 * @method static ProductQueryBuilder<static>|Product whereCreatedAt($value)
 * @method static ProductQueryBuilder<static>|Product whereCurrentPrice($value)
 * @method static ProductQueryBuilder<static>|Product whereDescription($value)
 * @method static ProductQueryBuilder<static>|Product whereFeatureValues(array $filters)
 * @method static ProductQueryBuilder<static>|Product whereId($value)
 * @method static ProductQueryBuilder<static>|Product whereIds(array $ids)
 * @method static ProductQueryBuilder<static>|Product whereInstruction($value)
 * @method static ProductQueryBuilder<static>|Product whereName($value)
 * @method static ProductQueryBuilder<static>|Product wherePreviewImage($value)
 * @method static ProductQueryBuilder<static>|Product wherePriceMax(float $maxPrice)
 * @method static ProductQueryBuilder<static>|Product wherePriceMin(float $minPrice)
 * @method static ProductQueryBuilder<static>|Product whereStatus($value)
 * @method static ProductQueryBuilder<static>|Product whereUpdatedAt($value)
 * @method static ProductQueryBuilder<static>|Product whereUserId($value)
 * @method static ProductQueryBuilder<static>|Product withAvailableStockItemsCount()
 * @method static ProductQueryBuilder<static>|Product withFeatures()
 * @method static ProductQueryBuilder<static>|Product withReservedStockItemsCount()
 * @method static ProductQueryBuilder<static>|Product withSoldStockItemsCount()
 * @method static ProductQueryBuilder<static>|Product withStockItemsCount()
 * @mixin \Eloquent
 */
class Product extends Model implements Seoble
{
    use HasFactory;

    protected $fillable = [
        'name',
        'current_price',
        'base_price',
        'status',
        'image',
        'category_id',
    ];

    protected function casts(): array
    {
        return [
            'current_price' => 'int',
            'base_price' => 'int',
            'status' => ProductStatus::class,
            'image' => ImageCast::class,
            'description' => CleanHtmlInput::class,
            'instruction' => CleanHtmlInput::class,
        ];
    }

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn() => new Price(
                $this->base_price,
                $this->current_price
            ),
            set: fn(Price $price) => [
                'base_price' => $price->getBasePrice(),
                'current_price' => $price->getCurrentPrice()
            ]
        );
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => Image::from($attributes['image'])?->getUrl(),
        );
    }

    public function isActive(): bool
    {
        return $this->status === ProductStatus::ACTIVE;
    }

    public function isDraft(): bool
    {
        return $this->status === ProductStatus::DRAFT;
    }

    public function isPaused(): bool
    {
        return $this->status === ProductStatus::PAUSED;
    }

    /**
     * Есть в наличие
     */
    public function hasAvailableStock(): bool
    {
        return $this->stockItems()->available()->exists();
    }

    /**
     * Возвращает количество доступных позиций для продажи
     * @return int
     */
    public function getQuantityInStock(): int
    {
        return $this->stockItems()->isAvailable()->count();
    }

    public function seo(): SeoBuilder
    {
        return new SeoBuilder()
            ->title($this->name)
            ->image($this->image_url);
    }

    public function toArray(): array
    {
        $array = parent::toArray();
        $array['price'] = $this->price->toArray();
        $array['image_url'] = $this->image_url;

        return $array;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, ProductFeatureValue::TABLE)
            ->withPivot('value')
            ->using(ProductFeatureValue::class);
    }

    public function stockItems(): HasMany|StockItemQueryBuilder
    {
        return $this->hasMany(StockItem::class);
    }

    public function feedbacks(): HasMany|FeedbackQueryBuilder
    {
        return $this->hasMany(Feedback::class);
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
