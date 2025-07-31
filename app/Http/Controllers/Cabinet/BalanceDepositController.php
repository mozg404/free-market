<?php

namespace App\Http\Controllers\Cabinet;

use App\Data\TransactionViewData;
use App\Http\Controllers\Controller;
use App\Services\PaymentManager;
use App\Services\Toaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class BalanceDepositController extends Controller
{
    public function __construct(
        private PaymentManager $payments,
        private Toaster $toaster,
    )
    {}

    public function index()
    {
        return Inertia::render('cabinet/balance/BalanceDeposit');
    }

    public function store(Request $request)
    {
        $date = $request->validate([
            'amount' => 'required|numeric'
        ]);

        $this->payments->topUpBalance(Auth::user(), $date['amount']);
        $this->toaster->success('Счет успешно пополнен');

        return redirect()->route('cabinet.balance');
    }
}
