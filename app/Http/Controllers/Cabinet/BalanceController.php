<?php

namespace App\Http\Controllers\Cabinet;

use App\Data\OrderViewData;
use App\Data\TransactionViewData;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class BalanceController extends Controller
{
    public function index()
    {
        $transactions = Transaction::query()->whereUser(Auth::id())->descOrder()->get();

        return Inertia::render('cabinet/balance/BalanceHistory', [
            'transactions' => TransactionViewData::collect($transactions),
        ]);
    }
}
