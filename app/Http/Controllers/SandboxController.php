<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class SandboxController extends Controller
{
    /**
     * @throws \JsonException
     */
    public function index(string $hash)
    {
        $decodedHash = base64_decode($hash);
        $data = json_decode($decodedHash, true);

        if (!isset($data['amount'])) {
            abort(404);
        }

        return Inertia::render('demo/SandboxIndexPage', [
            'amount' => $data['amount'],
            'hash' => $hash,
        ]);
    }

    public function success(string $hash): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('payment.callback', [
            'external_id' => $hash,
            'status' => 'success'
        ]);
    }

    public function failed(string $hash): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('payment.callback', [
            'external_id' => $hash,
            'status' => 'failed'
        ]);
    }

    public function cancelled(string $hash): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('payment.callback', [
            'external_id' => $hash,
            'status' => 'cancelled'
        ]);
    }
}
