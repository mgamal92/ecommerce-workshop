<?php

use App\Services\CustomerService;

return [

    /*
    |--------------------------------------------------------------------------
    | Customer Account Settings
    |--------------------------------------------------------------------------
    |
    */

    'category' => [
        'attach' => CustomerService::class . '@attachCategory',
        'detach' => CustomerService::class . '@detachCategory'
    ]

];
