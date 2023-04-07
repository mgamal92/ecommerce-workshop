<?php

namespace App\Custom\Editor;

use App\Contracts\ResourcesFlow;
use App\Http\Resources\ProductsResource;
use App\Models\Product;
use App\Services\ProductService;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class ProductFlow implements ResourcesFlow
{
    use HttpResponses;

    public function __construct(protected ProductService $productService, protected Product $model)
    {
    }

    public function index()
    {
        $products = $this->productService->getAllProducts($this->model);
        return count($products) > 0
            ? $this->success(ProductsResource::collection($products), 'products list')
            : $this->error(null, 'No Products Found', 404);
    }

    public function store(Request $request)
    {
        $product = $this->productService->store($this->model, $request->toArray());

        return new ProductsResource($product);
    }

    public function update(Request $request, $model)
    {
        $updateProduct = $this->productService->update($this->model, $model->id, $request->toArray());

        return new ProductsResource($updateProduct);
    }

    public function show($model)
    {
        return new ProductsResource($this->productService->show($this->model, $model->id));
    }
}
