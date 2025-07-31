<?php

namespace App\Services\Billing;

use App\Enum\PaymentSource;
use App\Models\Payment;

class ExternalPayment
{
    public function __construct(
        public Payment $payment,
        public string $toPayUrl,
    )
    {}
}