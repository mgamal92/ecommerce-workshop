<?php

namespace App\Services;

use App\Http\Resources\ProductsResource;
use App\Models\Product;
use App\Traits\HttpResponses;

class ProductService extends BaseServices
{
    use HttpResponses;

    protected $model;

    public function __construct()
    {
        $this->model = new Product();
    }

    public function getAllProducts($model)
    {
        $currentPage = request()->get('page', 1);

        return cache()->remember('products-' . $currentPage, 60 * 60 * 24, function () {
            return $this->retrieve($this->model);
        });
    }

    public function retrieve($model)
    {
        return $this->model->Filter(
            request()->get('category'),
            request()->get('name'),
            request()->get('price')
        )->paginate();
    }

    public function search($query)
    {
        $products = $this->model->search($query)->paginate();

        if (count($products) == 0) {
            return $this->error(null, 'no products found', 404);
        }

        return ProductsResource::collection($products);
    }
}
