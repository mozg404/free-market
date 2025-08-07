<?php

namespace App\Http\Controllers\Cabinet;

use App\Data\TransactionData;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\Billing\BillingService;
use App\Services\Toaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class BalanceController extends Controller
{
    public function __construct(
        private readonly BillingService $billing,
    )
    {}

    public function index()
    {
        $transactions = Transaction::query()
            ->whereUser(Auth::id())
            ->descOrder()
            ->paginate(10);

        return Inertia::render('cabinet/balance/BalanceIndex', [
            'pagination' => TransactionData::collect($transactions),
        ]);
    }

    public function deposit(Request $request)
    {
        $data = $request->validate([
            'amount' => 'required|numeric'
        ]);

        $externalPayment = $this->billing->createExternalReplenishmentPayment(Auth::user(), $data['amount']);

        return redirect($externalPayment->toPayUrl);
    }
}
