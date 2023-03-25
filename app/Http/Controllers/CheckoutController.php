<?php

namespace App\Http\Controllers;

use App\Services\CheckoutService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(protected CheckoutService $service)
    {
    }
    public function index()
    {
        return $this->service->summary();
    }
}
