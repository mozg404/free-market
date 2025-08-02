<?php

namespace App\Models;

use App\Contracts\Transactionable;
use App\Enum\PaymentSource;
use App\Enum\PaymentStatus;
use App\Builders\UserQueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $external_id
 * @property int $amount
 * @property PaymentStatus $status
 * @property PaymentSource|null $source
 * @property int|null $source_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereExternalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereSourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUserId($value)
 * @mixin \Eloquent
 */
class Payment extends Model implements Transactionable
{
    protected $casts = [
        'amount' => 'integer',
        'status' => PaymentStatus::class,
        'source' => PaymentSource::class,
    ];

    protected $fillable = ['user_id', 'amount', 'status', 'source', 'source_id'];

    /**
     * Создает новый платеж
     * @param User $user
     * @param int $amount
     * @param PaymentSource $source
     * @param int|null $sourceId
     * @return static
     */
    public static function new(User $user, int $amount, PaymentSource $source, int|null $sourceId = null): static
    {
        return self::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'status' => PaymentStatus::NEW,
            'source' => $source->value,
            'source_id' => $sourceId
        ]);
    }

    public function isReplenishmentSource(): bool
    {
        return $this->source === PaymentSource::REPLENISHMENT;
    }

    public function isOrderSource(): bool
    {
        return $this->source === PaymentSource::ORDER;
    }

    public function isPending(): bool
    {
        return $this->status === PaymentStatus::PENDING;
    }

    /**
     * Задает ID в кассе и переводит в режим ожидания
     * @param string $externalId
     * @return $this
     */
    public function toPend(string $externalId): static
    {
        $this->external_id = $externalId;
        $this->status = PaymentStatus::PENDING;
        $this->save();

        return $this;
    }

    public function isCompleted(): bool
    {
        return $this->status === PaymentStatus::COMPLETED;
    }

    public function toComplete(): static
    {
        $this->status = PaymentStatus::COMPLETED;
        $this->save();

        return $this;
    }

    public function toError(): static
    {
        $this->status = PaymentStatus::ERROR;
        $this->save();

        return $this;
    }

    public static function findByExternalId(string $hash): static|null
    {
        return self::where('external_id', $hash)->first();
    }

    public function getTransactionableType(): string
    {
        return $this::class;
    }

    public function getTransactionableId(): int
    {
        return $this->id;
    }

    public function user(): BelongsTo|UserQueryBuilder
    {
        return $this->belongsTo(User::class);
    }
}
