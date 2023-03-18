<?php

namespace App\Http\Controllers;

use App\Custom\CardPayment;
use App\Custom\WalletsPayment;
use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{

    public function __construct(protected PaymentService $service)
    {
    }
    /**
     * Payment Processing
     */
    public function processed(Request $request)
    {
        return $this->service->processPayment($request);
    }

    /**
     * Handle Callback Response.
     */
    public function response(Request $request)
    {
        return $request;
    }

    /**
     * Handle Callback transaction.
     */
    public function transaction(Request $request)
    {
        return $request;
    }
}
