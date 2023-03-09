<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Paymob Credentials
    |--------------------------------------------------------------------------
    -- ENV keys:
        PAYMOB_AUTH_URL=https://accept.paymob.com/api/auth/tokens
        PAYMOB_PAYMENT_URL=https://accept.paymob.com/api/acceptance/payment_keys
        PAYMOB_BASE_URL=https://accept.paymob.com/api/ecommerce/orders
        PAYMOB_IFRAME_URL=https://accept.paymobsolutions.com/api/acceptance/iframes/
        PAYMOB_WALLETS_URL=https://accept.paymob.com/api/acceptance/payments/pay
        PAYMOB_CARD_INTEGRATION_ID=3510156
        PAYMOB_CARD_IFRAME_ID=739375
    |--------------------------------------------------------------------------
    -- //*Example of the sent request by postman
        {
        "delivery_needed": "false",
        "amount_cents": "1000",
        "currency": "EGP",
        "shipping_fees":22,
        "total_amount":30,
        "payment_method":1,
        "items": [
            {
                "name": "ASC1515",
                    "amount_cents": "500000",
                    "description": "Smart Watch",
                    "quantity": "1",
                    "product_id": 1
            },
            {
                "name": "ASC1515",
                    "amount_cents": "500000",
                    "description": "Smart Watch",
                    "quantity": "1",
                    "product_id": 1
            }
            ],
        "shipping_data": {
            "email": "claudette09@exa.com", 
            "phone_number": "01010101010", 
            "first_name": "Clifford", 
            "last_name": "Nicolas",
            "apartment": "803",
            "floor": "42",
            "street": "Ethan Land", 
            "building": "8028", 
            "city": "Jaskolskiburgh", 
            "country": "CR", 
            "state": "Utah"
            }
        }
    */

    'auth' => [
        'api_key' => "ZXlKaGJHY2lPaUpJVXpVeE1pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SndjbTltYVd4bFgzQnJJam8zTVRRek9EY3NJbTVoYldVaU9pSXhOamM0TURrM01UQXhMakF3TVRVd05pSXNJbU5zWVhOeklqb2lUV1Z5WTJoaGJuUWlmUS51c1l2Z1JHcnlOZzVTUThzckh6NjlTNFpBc3NFQWdjMlNLMVNqNTRDem1XaXZJT0dqNTBOdkRoZ2dZNDlSdWJsYTl4RTB2Yl9SRXBnWU1lV0xOSlN1dw==",
    ],
];
