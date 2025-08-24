<?php

namespace App\Models;

use App\Builders\UserQueryBuilder;
use App\Casts\ImageCast;
use App\Contracts\Seoble;
use App\Support\Image;
use App\Support\SeoBuilder;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


/**
 *
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property bool $is_admin
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property ?Image $avatar
 * @property ?string $avatar_url
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $balance
 * @property int $positive_feedbacks_count
 * @property int $negative_feedbacks_count
 * @property float $seller_rating
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \App\Collections\ProductCollection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read int|null $transactions_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static UserQueryBuilder<static>|User newModelQuery()
 * @method static UserQueryBuilder<static>|User newQuery()
 * @method static UserQueryBuilder<static>|User query()
 * @method static UserQueryBuilder<static>|User whereAvatar($value)
 * @method static UserQueryBuilder<static>|User whereBalance($value)
 * @method static UserQueryBuilder<static>|User whereCreatedAt($value)
 * @method static UserQueryBuilder<static>|User whereEmail($value)
 * @method static UserQueryBuilder<static>|User whereEmailVerifiedAt($value)
 * @method static UserQueryBuilder<static>|User whereId($value)
 * @method static UserQueryBuilder<static>|User whereName($value)
 * @method static UserQueryBuilder<static>|User wherePassword($value)
 * @method static UserQueryBuilder<static>|User whereRememberToken($value)
 * @method static UserQueryBuilder<static>|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable implements Seoble, MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'is_admin' => 'bool',
            'balance' => 'int',
            'email_verified_at' => 'datetime',
//            'password' => 'hashed', // Убрано, так как хэш создает сервис регистрации
            'avatar' => ImageCast::class,
        ];
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: static fn (mixed $value, array $attributes) => Image::from($attributes['avatar'])?->getUrl(),
        );
    }

    /**
     * Изменяет аватар пользователя
     * @param Image $image
     * @return void
     * @throws \ErrorException
     */
    public function changeAvatar(Image $image): self
    {
        $this->avatar = $image->publishIfTemporary();
        $this->save();
        $this->fresh();

        return $this;
    }

    public function toArray(): array
    {
        $arr = parent::toArray();
        $arr['avatar'] = $this->avatar?->getUrl();

        return $arr;
    }

    public function hasEnoughBalance(int $amount): bool
    {
        return $this->balance >= $amount;
    }

    public function seo(): SeoBuilder
    {
        return new SeoBuilder()
            ->title('Пользователь ' . $this->name)
            ->image($this->avatar_url);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function newEloquentBuilder($query): UserQueryBuilder
    {
        return new UserQueryBuilder($query);
    }
}
