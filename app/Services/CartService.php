<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;

class CartService
{
    protected $model;

    public function __construct()
    {
        $this->model = new Cart();
    }

    public function addToCart(Product $product, $quantity) {
        $cart = $this->model->where('customer_id', Auth::user()->id)->first();
        if(!$cart) {
            $this->store($this->model, [
                'customer_id' => Auth::user()->id,
                'products' => [
                    ['product_id' => $product->id, 'quantity' => $quantity],
                ],
            ]);
        }
        else {
            $this->updateCart($cart, $product->id, $quantity);
        }
    }

    public function updateCart(Cart $cart, $product_id, $quantity) {
        $cart_has_product = $cart->whereJsonContains('products->product_id', $product_id)->first();
        $cart_has_product
            ? $this->increaseQuantity()
            : $this->appendProductToCart();
    }

    public function increaseQuantity() {

    }

    public function appendProductToCart() {

    }

}
