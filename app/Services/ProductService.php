<?php

namespace App\Services;

use App\Models\Product;

class ProductService extends BaseServices
{
    protected $model;

    public function __construct()
    {
        $this->model = new Product();
    }

    public function getAllProducts($model)
    {
        $currentPage = request()->get('page',1);
        
        return cache()->remember('products-'. $currentPage, 60*60*24, function(){
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
}
