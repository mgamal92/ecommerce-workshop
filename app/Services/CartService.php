<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use App\Traits\Helpers;
use Illuminate\Support\Facades\Auth;

class CartService extends BaseServices
{
    use Helpers;

    protected $model;
    protected $cart;
    protected $customer;

    public function __construct()
    {
        $this->model = new Cart();
        $this->customer = Auth::guard('api-customer')->check() ? Auth::guard('api-customer')->user()->id : null;
        $this->cart = Cart::where('customer_id', $this->customer)->first();
    }

    public function retrieve($model)
    {
        return $this->cart;
    }

    public function addToCart(Product $product, $quantity)
    {
        if (!$this->cart) {
            $this->cart = $this->store($this->model, [
                'customer_id' => Auth::guard('api-customer')->user()->id,
                'products' => [
                    [
                        'product_id' => (int)$product->id,
                        'quantity' => (int)$quantity,
                        'item_price' => (float) $product->price,
                        'subtotal' => $this->num_format((float) $product->price * $quantity)
                    ],
                ],
                'offer_sent' => 0
            ]);
        } else {
            $this->increaseOrAppendToCart($product, $quantity);
        }

        return $this->cart;
    }

    public function removeFromCart(Product $product)
    {
        $cart_products = $this->cart->products;
        foreach ($this->cart->products as $key => $prod) {
            if ($prod['product_id'] == $product->id) {
                unset($cart_products[$key]);
                break;
            }
        }

        if (count($cart_products) == 0) {
            $this->clearCart();
        }

        return $this->cart;
    }

    public function clearCart()
    {
        return $this->cart ? $this->cart->delete() : false;
    }

    /**************************************************************************/

    public function increaseOrAppendToCart(Product $product, $quantity)
    {
        $this->checkIfProductExistInCart($product->id)
            ? $this->increaseQuantity($product->id, $quantity)
            : $this->appendProductToCart($product->id, $quantity, $product->price);
    }

    public function increaseQuantity($product_id, $quantity)
    {
        $cart_products = $this->cart->products;
        foreach ($this->cart->products as $key => $product) {
            if ($product['product_id'] == $product_id) {
                $qty = $cart_products[$key]['quantity'] += $quantity;
                $cart_products[$key]['subtotal'] = $this->num_format((float) $cart_products[$key]['item_price'] * $qty);
                break;
            }
        }
        $this->cart->update(['products' => $cart_products]);
        return $this->cart;
    }

    public function decreaseQuantity($product_id, $quantity)
    {
        $cart_products = $this->cart->products;
        foreach ($this->cart->products as $key => $product) {
            if ($product['product_id'] == $product_id) {
                $qty = $cart_products[$key]['quantity'] -= $quantity;
                $cart_products[$key]['subtotal'] = $this->num_format((float) $cart_products[$key]['item_price'] * $qty);
                break;
            }
        }
        $this->cart->update(['products' => $cart_products]);
        return $this->cart;
    }

    public function appendProductToCart($product_id, $quantity, $price)
    {
        $cart_products = $this->cart->products;
        $cart_products[] = [
            'product_id' => (int)$product_id,
            'quantity' => (int)$quantity,
            'item_price' => (float) $price,
            'subtotal' => $this->num_format((float) $price * $quantity)
        ];

        $this->cart->update(['products' => $cart_products]);
    }


    public function checkIfProductExistInCart($product_id)
    {
        return $this->cart
            ? collect($this->cart->products)->where('product_id', $product_id)->first()
            : false;
    }

    public function getProductquantityInCart($product_id)
    {
        $cart_has_product = $this->checkIfProductExistInCart($product_id);
        return $cart_has_product ? $cart_has_product['quantity'] : 0;
    }
}
