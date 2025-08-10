<?php

namespace App\Models;

use App\Builders\ProductQueryBuilder;
use App\Builders\StockItemQueryBuilder;
use App\Casts\ImageCast;
use App\Collections\ArticleCollection;
use App\Collections\ProductCollection;
use App\Data\Products\ProductEditableData;
use App\Support\Image;
use App\Support\Price;
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

/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property int $current_price
 * @property int $base_price
 * @property bool $is_published
 * @property bool $is_available
 * @property \App\Support\Image|string|null|null $preview_image
 * @property string|null $description
 * @property string|null $activation_instruction
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $category_id
 * @property-read \App\Models\Category|null $category
 * @property-read \App\Models\ProductFeatureValue|null $pivot
 * @property-read Collection<int, \App\Models\Feature> $features
 * @property-read int|null $features_count
 * @property Price $price
 * @property-read Collection<int, \App\Models\StockItem> $stockItems
 * @property-read int|null $stock_items_count
 * @property-read \App\Models\User $user
 * @method static Product find(int $id, array $columns = ['*'])
 * @method static ProductCollection<int, static> all($columns = ['*'])
 * @method static ProductQueryBuilder<static>|Product descOrder()
 * @method static \Database\Factories\ProductFactory factory($count = null, $state = [])
 * @method static ProductQueryBuilder<static>|Product filterFromArray(array $data)
 * @method static ProductQueryBuilder<static>|Product for(\App\Models\User|\App\Models\Category $object)
 * @method static ProductQueryBuilder<static>|Product forCategory(\App\Models\Category|int $category)
 * @method static ProductQueryBuilder<static>|Product forUser(\App\Models\User|int $user)
 * @method static ProductCollection<int, static> get($columns = ['*'])
 * @method static ProductQueryBuilder<static>|Product getPrices()
 * @method static ProductQueryBuilder<static>|Product newModelQuery()
 * @method static ProductQueryBuilder<static>|Product newQuery()
 * @method static ProductQueryBuilder<static>|Product onlyDiscounted()
 * @method static ProductQueryBuilder<static>|Product orderByActualPrice(string $direction = 'asc')
 * @method static ProductQueryBuilder<static>|Product query()
 * @method static ProductQueryBuilder<static>|Product searchByName(string $search)
 * @method static ProductQueryBuilder<static>|Product whereActivationInstruction($value)
 * @method static ProductQueryBuilder<static>|Product whereBasePrice($value)
 * @method static ProductQueryBuilder<static>|Product whereCategoryId($value)
 * @method static ProductQueryBuilder<static>|Product whereCreatedAt($value)
 * @method static ProductQueryBuilder<static>|Product whereCurrentPrice($value)
 * @method static ProductQueryBuilder<static>|Product whereDescription($value)
 * @method static ProductQueryBuilder<static>|Product whereFeatureValues(array $filters)
 * @method static ProductQueryBuilder<static>|Product whereId($value)
 * @method static ProductQueryBuilder<static>|Product whereIds(array $ids)
 * @method static ProductQueryBuilder<static>|Product whereIsAvailable($value)
 * @method static ProductQueryBuilder<static>|Product whereIsPublish($value)
 * @method static ProductQueryBuilder<static>|Product whereName($value)
 * @method static ProductQueryBuilder<static>|Product wherePreviewImage($value)
 * @method static ProductQueryBuilder<static>|Product wherePriceBetween(?int $minPrice, ?int $maxPrice)
 * @method static ProductQueryBuilder<static>|Product wherePriceMax(int $maxPrice)
 * @method static ProductQueryBuilder<static>|Product wherePriceMin(int $minPrice)
 * @method static ProductQueryBuilder<static>|Product whereUpdatedAt($value)
 * @method static ProductQueryBuilder<static>|Product whereUserId($value)
 * @method static ProductQueryBuilder<static>|Product withAvailableStockItemsCount()
 * @method static ProductQueryBuilder<static>|Product withFeatures()
 * @method static ProductQueryBuilder<static>|Product withReservedStockItemsCount()
 * @method static ProductQueryBuilder<static>|Product withShop()
 * @method static ProductQueryBuilder<static>|Product withSoldStockItemsCount()
 * @method static ProductQueryBuilder<static>|Product withStockItemsCount()
 * @mixin \Eloquent
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'current_price',
        'base_price',
        'is_available',
        'preview_image',
        'category_id',
    ];

    protected function casts(): array
    {
        return [
            'current_price' => 'int',
            'base_price' => 'int',
            'is_available' => 'boolean',
            'preview_image' => ImageCast::class,
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

    public static function new(User $user, ProductEditableData $data): Product
    {
        return DB::transaction(function () use ($user, $data) {
            $model = new static();
            $model->user_id = $user->id;
            $model->saturate($data);
            $model->save();
            $model->featuresAttachFrom($data->features ?? []);

            return $model;
        });
    }

    public function edit(ProductEditableData $data): void
    {
        DB::transaction(function () use ($data) {
            $this->saturate($data);
            $this->save();
            $this->features()->detach();
            $this->featuresAttachFrom($data->features ?? []);
        });
    }

    public function featuresAttachFrom(array $features): void
    {
        foreach ($features as $id => $value) {
            if (isset($value)) {
                $this->features()->attach($id, ['value' => $value]);
            }
        }
    }

    private function saturate(ProductEditableData $data): void
    {
        $this->name = $data->name;
        $this->category_id = $data->category_id;
        $this->price = Price::fromBaseAndDiscount($data->price_base, $data->price_discount);
        $this->description = $data->description;

        if (isset($data->preview_image)) {
            $this->preview_image = $data->preview_image;
        }
    }

    public function isPublished(): bool
    {
        return $this->is_published;
    }

    public function isUnpublished(): bool
    {
        return !$this->is_published;
    }

    /**
     * Публикует товар
     */
    public function publish(): void
    {
        $this->is_published = true;
        $this->save();
    }

    /**
     * Снимает с публикации
     */
    public function unpublish(): void
    {
        $this->is_published = false;
        $this->save();
    }

    public function isAvailable(): bool
    {
        return $this->is_available;
    }

    public function isNotAvailable(): bool
    {
        return !$this->is_available;
    }

    /**
     * Разрешает продажу
     */
    public function markAsAvailable(): void
    {
        $this->is_available = true;
        $this->save();
    }

    /**
     * Запрещает продажу
     */
    public function markAsUnavailable(): void
    {
        $this->is_available = false;
        $this->save();
    }

    /**
     * Проверяет достаточно ли доступных позиций для продажи
     * @param int $amount
     * @return bool
     */
    public function hasEnoughStockItems(int $amount): bool
    {
        return $this->getAvailableStockItemsCount() >= $amount;
    }

    /**
     * Возвращает количество доступных позиций для продажи
     * @return int
     */
    public function getAvailableStockItemsCount(): int
    {
        return $this->stockItems()->isAvailable()->count();
    }

    public function toArray(): array
    {
        $array = parent::toArray();
        $array['price'] = $this->price->toArray();

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
