<?php

namespace App\Custom;

use App\Contracts\PaymentFlow;

class CardPayment implements PaymentFlow
{
    public function buildUrl(string $token, $phoneNumber = null)
    {
        return config('paymob.keys.PAYMOB_IFRAME_URL') . config('paymob.keys.PAYMOB_CARD_IFRAME_ID') . "?payment_token=$token";
    }
}
