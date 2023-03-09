<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrdersResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\OrderService;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use HttpResponses;

    protected OrderService $orderService;

    protected $model;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
        $this->model = new Order();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //List Orders
        return OrdersResource::collection($this->orderService->retrieveWithItems());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return new OrdersResource($this->orderService->show($this->model, $order->id));
    }
}
