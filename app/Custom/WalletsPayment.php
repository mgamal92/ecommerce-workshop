<?php

namespace App\Custom;

use App\Contracts\PaymentFlow;
use Illuminate\Support\Facades\Http;

class WalletsPayment implements PaymentFlow
{
    public function buildUrl(string $token, $phoneNumber = null)
    {
        $json = [
            "source" => [
                "identifier" => $phoneNumber,
                "subtype" => "WALLET"
            ],
            "payment_token" => $token
        ];
        return Http::acceptJson()->post(env('PAYMOB_WALLETS_URL'), $json)->collect();
    }
}
