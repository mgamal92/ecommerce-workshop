<?php

use App\Services\CustomerService;

return [

    /*
    |--------------------------------------------------------------------------
    | Customer Account Settings
    |--------------------------------------------------------------------------
    |
    */

    'preferences' => [
        'attach' => CustomerService::class . '@attachCategory',
        'detach' => CustomerService::class . '@detachCategory',
        'set' => CustomerService::class . '@setLang',
        'unset' => CustomerService::class . '@unsetLang'
    ],
];
