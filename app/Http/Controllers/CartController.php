<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartsResource;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Services\CartService;
use App\Traits\HttpResponses;

class CartController extends Controller
{
    use HttpResponses;

    protected CartService $cartService;
    protected $model;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
        $this->model = new Cart();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cart = $this->cartService->retrieve($this->model);
        return count($cart) > 0
            ? $this->success(CartsResource::collection($cart),'cart items list')
            : $this->error(null, 'No Cart Items Found', 404);
    }

    /**
     * create cart in not exist.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {
        //
    }
}
