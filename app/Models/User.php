<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Casts\ImageCast;
use App\Contracts\Transactionable;
use App\Enum\TransactionType;
use App\Support\Image;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;


/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property Image|null $avatar
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $balance
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \App\Collections\ProductCollection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read int|null $transactions_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
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
            'balance' => 'int',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'avatar' => ImageCast::class,
        ];
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

    /**
     * @throws \Throwable
     */
    public function deposit(int $amount, TransactionType $type, Transactionable $transactionable = null): Transaction
    {
        return DB::transaction(function () use ($amount, $type, $transactionable) {
            $this->increment('balance', $amount);

            return $this->transactions()->create([
                'amount' => $amount,
                'type' => $type->value,
                'transactionable_type' => $transactionable?->getTransactionableType(),
                'transactionable_id' => $transactionable?->getTransactionableId(),
                'created_at' => now()->toDateTimeString(),
            ]);
        });
    }

    /**
     * @throws \Throwable
     */
    public function withdraw(int $amount, TransactionType $type, Transactionable $transactionable = null): Transaction
    {
        return DB::transaction(function () use ($amount, $type, $transactionable) {
            $this->decrement('balance', $amount);

            return $this->transactions()->create([
                'amount' => -$amount,
                'type' => $type->value,
                'transactionable_type' => $transactionable?->getTransactionableType(),
                'transactionable_id' => $transactionable?->getTransactionableId(),
                'created_at' => now()->toDateTimeString(),
            ]);
        });
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
}
