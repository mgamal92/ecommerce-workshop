<?php

namespace App\Services;

use App\Http\Controllers\CartController;
use App\Traits\Helpers;
use Illuminate\Http\Request;

class CheckoutService
{
    use Helpers;

    public function __construct(protected CartController $cartController)
    {
    }

    public function summary()
    {
        if ($this->cartController->index()->status() == 404) {
            return $this->cartController->index();
        }

        $data = $this->cartController->index()->getData();

        $subtotals = [];
        foreach ($data->data->products as $product) {
            $subtotals[] = (int) $product->subtotal;
        }
        $data->total_amount = $this->num_format((float) array_sum($subtotals));

        return $data;
    }
}
