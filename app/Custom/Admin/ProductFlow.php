<?php

namespace App\Custom\Admin;

use App\Contracts\ResourcesFlow;
use App\Custom\ProductBaseResource;
use App\Http\Resources\ProductsResource;

class ProductFlow extends ProductBaseResource implements ResourcesFlow
{
    // override index() BaseResource
    public function index()
    {
        $products = $this->service->getAllProducts($this->product);
        return count($products) > 0
            ? (ProductsResource::collection($products))->additional(['user-type' => 'admin'])
            : $this->error(null, 'No Products Found', 404);
    }
}
