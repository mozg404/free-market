<?php

namespace App\Http\Controllers\Cabinet;

use App\Data\TransactionViewData;
use App\Enum\PaymentSource;
use App\Http\Controllers\Controller;
use App\Services\Billing\BillingService;
use App\Services\PaymentManager;
use App\Services\Toaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class BalanceDepositController extends Controller
{
    public function __construct(
        private BillingService $billing,
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

        $externalPayment = $this->billing->createExternalReplenishmentPayment(Auth::user(), $date['amount']);

        return redirect($externalPayment->toPayUrl);
    }
}
