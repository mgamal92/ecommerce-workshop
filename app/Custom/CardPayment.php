<?php

namespace App\Custom;

use App\Contracts\PaymentFlow;

class CardPayment implements PaymentFlow
{
    public function buildUrl(string $token, $phoneNumber = null)
    {
        return env('PAYMOB_IFRAME_URL') . env('PAYMOB_CARD_IFRAME_ID') . "?payment_token=$token";
    }
}
