<?php

namespace App\Services;

class ProductService extends BaseServices
{
    public function getAllProducts($model)
    {
        $currentPage = request()->get('page',1);
        
        return cache()->remember('products-'. $currentPage, 60*60*24, function(){
            return $this->retrieve(new \App\Models\Product);
        });
    }
}
