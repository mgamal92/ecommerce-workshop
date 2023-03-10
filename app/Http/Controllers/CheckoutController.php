<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\CartsResource;
use App\Http\Resources\CheckoutResource;
use App\Models\Cart;
use App\Models\Customer;
use App\Services\CartService;
class CheckoutController extends Controller
{
    protected CartService $cartService;
    protected $cart;
    protected $cartId;
    private $estimatedShipping = 0;
    private $estimatedTax = 0;
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
        $this->cart = $this->cartService->retrieve(new Cart());
        $cartArr= $this->cart->toArray();
        $this->cartId = $cartArr['id'];
    }
    public function getCart($cartId){
        return Cart::where('id',$cartId)->first();
    }
    public function calculateSubTotal($cartId){
        $cart = $this->getCart($cartId);
        $subTotal = 0;
        foreach($cart->products as $key => $product){
            $subTotal+=$product['price']*$product['quantity'];
        }
        return $subTotal;  
    }
    public function calculateTaxes($cartId){
        $cart = $this->getCart($cartId);
        $taxes = $this->estimatedShipping + $this->estimatedTax;
        return $taxes;
    } 
    public function calculateTotal($cartId){
        $total = $this->calculateSubTotal($cartId)+$this->calculateTaxes($cartId);
        return $total;
    }
    public function checkout(){
        $customer = Customer::where('id',$this->cart->customer_id)->first();
        $cartItems = new CartsResource($this->cart);
        $subTotal = $this->calculateSubTotal($this->cartId);
        $taxes = $this->calculateTaxes($this->cartId);
        $total = $this->calculateTotal($this->cartId);
        return new CheckoutResource($customer,$cartItems,$subTotal,$taxes,$total);
    }   
}
