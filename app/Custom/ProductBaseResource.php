<?php

namespace App\Custom;

use App\Contracts\ResourcesFlow;
use App\Http\Resources\ProductsResource;
use App\Models\Product;
use App\Services\ProductService;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

abstract class ProductBaseResource implements ResourcesFlow
{
    use HttpResponses;

    public function __construct(protected ProductService $service, protected Product $product)
    {
    }

    public function index()
    {
        $products = $this->service->getAllProducts($this->product);
        return count($products) > 0
            ? ProductsResource::collection($products)
            : $this->error(null, 'No Products Found', 404);
    }

    public function store(Request $request)
    {
        $product = $this->service->store($this->product, $request->toArray());

        return new ProductsResource($product);
    }

    public function update(Request $request, $model)
    {
        $updateProduct = $this->service->update($this->product, $model->id, $request->toArray());

        return new ProductsResource($updateProduct);
    }

    public function show($model)
    {
        return new ProductsResource($this->service->show($this->product, $model->id));
    }
}
