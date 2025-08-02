<?php

namespace App\Models;

use App\Collections\ArticleCollection;
use App\Collections\ProductCollection;
use App\Data\Products\BaseProductData;
use App\Builders\ProductQueryBuilder;
use App\Builders\StockItemQueryBuilder;
use App\Support\Filepond\Image;
use App\Support\Filepond\ImageStub;
use App\Support\Phone;
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
 * @property int $price_base
 * @property int|null $price_discount
 * @property bool $is_available
 * @property \App\Support\Filepond\Image|string|null|null $preview_image
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $category_id
 * @property Price $price
 * @property-read \App\Models\Category|null $category
 * @property-read \App\Models\ProductFeatureValue|null $pivot
 * @property-read Collection<int, \App\Models\Feature>|Feature[] $features
 * @property-read int|null $features_count
 * @property-read Collection<int, \App\Models\StockItem> $stockItems
 * @property-read int|null $stock_items_count
 * @property-read \App\Models\User $user
 * @method static ProductCollection<int, static> all($columns = ['*'])
 * @method static \Database\Factories\ProductFactory factory($count = null, $state = [])
 * @method static ProductCollection<int, static> get($columns = ['*'])
 * @method static ProductQueryBuilder<static>|Product getPrices()
 * @method static ProductQueryBuilder<static>|Product newModelQuery()
 * @method static ProductQueryBuilder<static>|Product newQuery()
 * @method static ProductQueryBuilder<static>|Product query()
 * @method static ProductQueryBuilder<static>|Product searchByName(string $search)
 * @method static ProductQueryBuilder<static>|Product whereCategoryId($value)
 * @method static ProductQueryBuilder<static>|Product whereCreatedAt($value)
 * @method static ProductQueryBuilder<static>|Product whereDescription($value)
 * @method static ProductQueryBuilder<static>|Product whereId($value)
 * @method static ProductQueryBuilder<static>|Product whereIds(array $ids)
 * @method static ProductQueryBuilder<static>|Product whereIsAvailable($value)
 * @method static ProductQueryBuilder<static>|Product whereName($value)
 * @method static ProductQueryBuilder<static>|Product wherePreviewImage($value)
 * @method static ProductQueryBuilder<static>|Product wherePriceBase($value)
 * @method static ProductQueryBuilder<static>|Product wherePriceDiscount($value)
 * @method static ProductQueryBuilder<static>|Product whereUpdatedAt($value)
 * @method static ProductQueryBuilder<static>|Product whereUser(int $id)
 * @method static ProductQueryBuilder<static>|Product whereUserId($value)
 * @method static ProductQueryBuilder<static>|Product withAvailableStockItemsCount()
 * @method static ProductQueryBuilder<static>|Product withShop()
 * @mixin \Eloquent
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price_base',
        'price_discount',
        'is_available',
        'preview_image',
        'category_id',
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
            set: fn (Price $price) => [
                'price_base' => $price->base,
                'price_discount' => $price->discount,
            ],
        );
    }

    protected function previewImage(): Attribute
    {
        return Attribute::make(
            get: static function (string|null $id) {
                if (is_string($id) && Image::exists($id)) {
                    return Image::from($id);
                }

                return null;
            },
            set: static function (Image|string|null $id) {
                if (is_a($id, Image::class)) {
                    return $id;
                }

                if (is_string($id) && Image::exists($id)) {
                    return Image::from($id);
                }

                return null;
            },
        );
    }

    public static function new(User $user, BaseProductData $data): Product
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

    public function edit(BaseProductData $data): void
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

    private function saturate(BaseProductData $data): void
    {
        $this->name = $data->name;
        $this->category_id = $data->categoryId;
        $this->price = new Price($data->priceBase, $data->priceDiscount);
        $this->description = $data->description;

        if (isset($data->previewImage)) {
            $this->preview_image = $data->previewImage;
        }
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
