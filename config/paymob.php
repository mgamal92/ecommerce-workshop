<?php

use App\Custom\CardPayment;
use App\Custom\WalletsPayment;
use App\Models\Order;

return [
    /*
    |--------------------------------------------------------------------------
    | Paymob configuration
    |--------------------------------------------------------------------------
    */

    'payments' => [
        Order::CARD_PAYMENT => CardPayment::class,
        Order::WALLET_PAYMENT => WalletsPayment::class
    ],

    'auth' => [
        'api_key' => "ZXlKaGJHY2lPaUpJVXpVeE1pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SndjbTltYVd4bFgzQnJJam8zTVRRek9EY3NJbTVoYldVaU9pSXhOamM0TURrM01UQXhMakF3TVRVd05pSXNJbU5zWVhOeklqb2lUV1Z5WTJoaGJuUWlmUS51c1l2Z1JHcnlOZzVTUThzckh6NjlTNFpBc3NFQWdjMlNLMVNqNTRDem1XaXZJT0dqNTBOdkRoZ2dZNDlSdWJsYTl4RTB2Yl9SRXBnWU1lV0xOSlN1dw=="
    ],

    'keys' => [
        'PAYMOB_AUTH_URL' => 'https://accept.paymob.com/api/auth/tokens',
        'PAYMOB_PAYMENT_URL' => 'https://accept.paymob.com/api/acceptance/payment_keys',
        'PAYMOB_BASE_URL' => 'https://accept.paymob.com/api/ecommerce/orders',
        'PAYMOB_IFRAME_URL' => 'https://accept.paymobsolutions.com/api/acceptance/iframes/',
        'PAYMOB_WALLETS_URL' => 'https://accept.paymob.com/api/acceptance/payments/pay',
        'PAYMOB_CARD_INTEGRATION_ID' => '3510156',
        'PAYMOB_CARD_IFRAME_ID' => '739375'
    ]
];
