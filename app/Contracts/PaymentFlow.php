<?php

namespace App\Contracts;

interface PaymentFlow
{
    public function buildUrl(string $token, $phoneNumber = null);
}
