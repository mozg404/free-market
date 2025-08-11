<?php

namespace App\Http\Controllers\Cabinet;

use App\Contracts\PaymentGateway;
use App\Data\TransactionData;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class BalanceController extends Controller
{
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

    public function deposit(Request $request, PaymentGateway $gateway)
    {
        $data = $request->validate([
            'amount' => 'required|numeric'
        ]);

        return redirect($gateway->getPaymentUrl(
            $gateway->createDeposit(auth()->user(), $data['amount'])
        ));
    }
}
