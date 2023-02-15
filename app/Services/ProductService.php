<?php

namespace App\Services;

class ProductService extends BaseServices
{
    public function getAllProducts($model)
    {
        return cache()->remember('products', 60*60*24, function(){
            return $this->retrieve(new \App\Models\Product);
        });
    }
}
