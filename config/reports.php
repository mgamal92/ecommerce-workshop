<?php

use App\Http\Controllers\ReportController;

return [

    /*
    |--------------------------------------------------------------------------
    | return specified Report method from ReportController
    |--------------------------------------------------------------------------
    */

    'specificPeriodReports' => [
        'customers' => ReportController::class . '@customersWithinPeriod',
        'users' => ReportController::class . '@membersWithinPeriod',
        'orders' => ReportController::class . '@ordersWithinPeriod',
    ],
];
