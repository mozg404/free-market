<?php

namespace App\Services;

use App\Enum\TransactionType;
use App\Models\Transaction;
use App\Models\User;

class PaymentManager
{
    /**
     * Пополнить счет на $amount сумму
     * @param User $user
     * @param int $amount
     * @return Transaction
     * @throws \Throwable
     */
    public function topUpBalance(User $user, int $amount): Transaction
    {
        return $user->deposit($amount, TransactionType::INFLOW);
    }
}