<?php

namespace App\Models;

use App\Enum\TransactionType;
use App\QueryBuilders\TransactionQueryBuilder;
use App\QueryBuilders\UserQueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $amount
 * @property TransactionType $type
 * @property string $transactionable_type
 * @property int $transactionable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property-read Model|\Eloquent $transactionable
 * @property-read \App\Models\User $user
 * @method static TransactionQueryBuilder<static>|Transaction newModelQuery()
 * @method static TransactionQueryBuilder<static>|Transaction newQuery()
 * @method static TransactionQueryBuilder<static>|Transaction query()
 * @method static TransactionQueryBuilder<static>|Transaction whereAmount($value)
 * @method static TransactionQueryBuilder<static>|Transaction whereCreatedAt($value)
 * @method static TransactionQueryBuilder<static>|Transaction whereId($value)
 * @method static TransactionQueryBuilder<static>|Transaction whereTransactionableId($value)
 * @method static TransactionQueryBuilder<static>|Transaction whereTransactionableType($value)
 * @method static TransactionQueryBuilder<static>|Transaction whereType($value)
 * @method static TransactionQueryBuilder<static>|Transaction whereUserId($value)
 * @mixin \Eloquent
 */
class Transaction extends Model
{
    public $timestamps = false;

    protected $casts = [
        'amount' => 'integer',
        'type' => TransactionType::class,
        'created_at' => 'datetime',
    ];

    protected $fillable = ['amount', 'type', 'transactionable_type', 'transactionable_id', 'created_at'];

    public function user(): BelongsTo|UserQueryBuilder
    {
        return $this->belongsTo(User::class);
    }

    public function transactionable(): MorphTo
    {
        return $this->morphTo();
    }

    public function newEloquentBuilder($query): TransactionQueryBuilder
    {
        return new TransactionQueryBuilder($query);
    }
}
