<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\CartsResource;
use App\Http\Resources\CheckoutResource;
use App\Models\Cart;
use App\Models\Customer;
use App\Services\CartService;
use App\Http\Resources\CustomersResource;
use App\Models\Product;
class CheckoutController extends Controller
{
    protected CartService $cartService;
    protected $cart;
    protected $cartId;
    private $estimatedShipping = 8;
    private $estimatedTax = 8;
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
        foreach($cart->products as $key => $value){
            $product = Product::where('id',$value['product_id'])->first();
            $subTotal+=$product['price']*$value['quantity'];
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
        $customer = new CustomersResource(Customer::where('id',$this->cart->customer_id)->first());
        $cartItems = new CartsResource($this->cart);
        $subTotal = $this->calculateSubTotal($this->cartId);
        $taxes = $this->calculateTaxes($this->cartId);
        $total = $this->calculateTotal($this->cartId);
        $summary = [
            'customer'      =>  $customer,
            'cart_items'    =>  $cartItems,
            'sub_total'     =>  $subTotal,
            'taxes'         =>  $taxes,
            'total'         =>  $total
        ];
        
        return $summary;
    }   
}
