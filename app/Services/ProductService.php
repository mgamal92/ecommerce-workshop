<?php

namespace App\Services;

use App\Models\Product;

class ProductService extends BaseServices
{
    protected $model;
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->model = new Product();
        $this->cartService = $cartService;
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

    public function checkIfProductExist($id)
    {
        return $this->model->find($id);
    }

    public function checkQuantity($quantity, Product $product)
    {
        $qty = $this->cartService->getProductquantityInCart($product->id)
            ? $quantity+$this->cartService->getProductquantityInCart($product->id)
            : $quantity;

        return $qty > 0 && $qty <= $product->quantity;
    }
}
