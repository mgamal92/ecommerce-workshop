<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartService extends BaseServices
{
    protected $model;
    protected $cart;

    public function __construct()
    {
        $this->model = new Cart();
        $this->cart = $this->model->where('customer_id', 1 /*Auth::user()->id*/)->first();
    }

    public function addToCart(Product $product, $quantity) {
        if(!$this->cart) {
            $this->cart = $this->store($this->model, [
                'customer_id' => 1 /*Auth::user()->id*/,
                'products' => [
                    ['product_id' => (int)$product->id, 'quantity' => (int) $quantity],
                ],
            ]);
        }
        else {
            $this->updateCart($product, $quantity);
        }

        return $this->cart;
    }

    public function updateCart(Product $product, $quantity) {
        $this->checkIfProductExistInCart($product->id)
            ? $this->increaseQuantity($product->id, $quantity)
            : $this->appendProductToCart($product->id, $quantity);
    }

    public function increaseQuantity($product_id, $quantity) {
        $cart_products = $this->cart->products;
        foreach($this->cart->products as $key => $product) {
            if($product['product_id'] == $product_id) {
                $cart_products[$key]['quantity'] += $quantity;
                break;
            }
        }
        $this->cart->update(['products' => $cart_products]);
    }

    public function appendProductToCart($product_id, $quantity) {
        $cart_products = $this->cart->products;
        $cart_products[] = ['product_id' => (int)$product_id, 'quantity' => (int)$quantity];

        $this->cart->update(['products' => $cart_products]);
    }


    public function checkIfProductExistInCart($product_id) {
        return collect($this->cart->products)->where('product_id', $product_id)->first();
    }

    public function getProductquantityInCart($product_id) {
        $cart_has_product = $this->checkIfProductExistInCart($product_id);
        return $cart_has_product ? $cart_has_product['quantity'] : 0;
    }

}
