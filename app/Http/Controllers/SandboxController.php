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

        return Inertia::render('Sandbox', [
            'amount' => $data['amount'],
            'hash' => $hash,
        ]);
    }
}
