<?php

namespace App\Data;

use App\Enum\TransactionType;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

class TransactionViewData extends Data
{
    public function __construct(
        public int $id,
        public TransactionType $type,
        public int $amount,
        public Carbon $createdAt,
    ) {}

    public static function fromModel(Transaction $transaction): static
    {
        return new self(
            id: $transaction->id,
            type: $transaction->type,
            amount: $transaction->amount,
            createdAt: $transaction->created_at,
        );
    }
}
