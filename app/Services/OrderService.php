<?php

namespace App\Services;

use App\Models\Order;

class OrderService extends BaseServices
{
    public function __construct(protected Order $order)
    {
    }

    public function retrieveWithItems()
    {
        return $this->order->with('items')->get();
    }
}
