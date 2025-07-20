<?php

namespace App\Models;

use App\QueryBuilders\ShopQueryBuilder;
use App\Support\Inn;
use App\Support\Phone;
use Database\Factories\ShopFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property Inn|null $inn
 * @property string|null $address
 * @property Phone|null $phone
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\ShopFactory factory($count = null, $state = [])
 * @method static ShopQueryBuilder<static>|Shop newModelQuery()
 * @method static ShopQueryBuilder<static>|Shop newQuery()
 * @method static ShopQueryBuilder<static>|Shop query()
 * @method static ShopQueryBuilder<static>|Shop whereAddress($value)
 * @method static ShopQueryBuilder<static>|Shop whereCreatedAt($value)
 * @method static ShopQueryBuilder<static>|Shop whereDescription($value)
 * @method static ShopQueryBuilder<static>|Shop whereId($value)
 * @method static ShopQueryBuilder<static>|Shop whereInn($value)
 * @method static ShopQueryBuilder<static>|Shop whereName($value)
 * @method static ShopQueryBuilder<static>|Shop wherePhone($value)
 * @method static ShopQueryBuilder<static>|Shop whereSlug($value)
 * @method static ShopQueryBuilder<static>|Shop whereUpdatedAt($value)
 * @method static ShopQueryBuilder<static>|Shop whereUserId($value)
 * @mixin \Eloquent
 */
class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    public static function new(User $user, string $name, Inn $inn, ?string $description = null, ?string $address = null, ?Phone $phone = null): static
    {
        $shop = new Shop();
        $shop->user_id = $user->id;
        $shop->name = $name;
        $shop->slug = Str::slug($name);
        $shop->inn = $inn;
        $shop->description = $description ?? '';
        $shop->address = $address ?? '';
        $shop->phone = $phone;
        $shop->save();

        return $shop;
    }

    public function edit(string $name, Inn $inn, ?string $description = null, ?string $address = null, ?Phone $phone = null): static
    {
        $this->name = $name;
        $this->slug = Str::slug($name);
        $this->inn = $inn;
        $this->description = $description ?? '';
        $this->address = $address ?? '';
        $this->phone = $phone;
        $this->save();

        return $this;
    }

    protected function phone(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => isset($value) ? Phone::restore($value) : null,
            set: function (Phone|string|null $value) {
                if (is_string($value)) {
                    return Phone::create($value);
                }

                return $value;
            },
        );
    }

    protected function inn(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => isset($value) ? Inn::restore($value) : null,
            set: fn (Inn|string $value) => is_string($value) ? Inn::create($value) : $value,
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function toArray(): array
    {
        $array = parent::toArray();

        if (key_exists('phone', $array)) {
            $array['phone'] = $this->phone?->number ?? null;
        }

        if (key_exists('inn', $array)) {
            $array['inn'] = $this->inn?->number ?? null;
        }

        return $array;
    }

    public function newEloquentBuilder($query): ShopQueryBuilder
    {
        return new ShopQueryBuilder($query);
    }

    protected static function newFactory(): ShopFactory|\Illuminate\Database\Eloquent\Factories\Factory
    {
        return ShopFactory::new();
    }
}
