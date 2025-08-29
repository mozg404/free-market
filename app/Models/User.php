<?php

namespace App\Models;

use App\Builders\UserQueryBuilder;
use App\Contracts\Seoble;
use App\Support\SeoBuilder;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property bool $is_admin
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property ?array $avatar
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
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
class User extends Authenticatable implements Seoble, MustVerifyEmail, HasMedia
{
    public const string MEDIA_COLLECTION_AVATAR = 'avatar';

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, InteractsWithMedia;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::MEDIA_COLLECTION_AVATAR)
            ->singleFile()
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('thumb')
                    ->width(36)
                    ->height(36)
                    ->format('webp')
                    ->quality(75)
                    ->optimize()
                    ->nonQueued();

                $this
                    ->addMediaConversion('thumb_2')
                    ->width(72)
                    ->height(72)
                    ->format('webp')
                    ->quality(75)
                    ->optimize()
                    ->nonQueued();

                $this
                    ->addMediaConversion('medium')
                    ->width(150)
                    ->height(150)
                    ->format('webp')
                    ->quality(80)
                    ->optimize()
                    ->nonQueued();

                $this
                    ->addMediaConversion('large')
                    ->width(300)
                    ->height(300)
                    ->format('webp')
                    ->quality(85)
                    ->optimize()
                    ->nonQueued();
            });
    }

    protected function casts(): array
    {
        return [
            'is_admin' => 'bool',
            'balance' => 'int',
            'email_verified_at' => 'datetime',
//            'password' => 'hashed', // Убрано, так как хэш создает сервис регистрации
        ];
    }

    protected function avatar(): Attribute
    {
        return Attribute::get(function () {
            $media = $this->getFirstMedia(self::MEDIA_COLLECTION_AVATAR);

            if (!$media) {
                return null;
            }

            return [
                'thumb' => $media->getUrl('thumb'),
                'thumb_2' => $media->getUrl('thumb_2'),
                'medium' => $media->getUrl('medium'),
                'large' => $media->getUrl('large'),
                'original' => $media->getUrl(),
            ];
        });
    }

    public function toArray(): array
    {
        $arr = parent::toArray();
        $arr['avatar'] = $this->avatar;

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
