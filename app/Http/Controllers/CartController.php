<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddToCartRequest;
use App\Http\Resources\CartsResource;
use App\Services\ProductService;
use App\Services\CartService;
use App\Traits\HttpResponses;
use App\Models\Cart;


class CartController extends Controller
{
    use HttpResponses;

    protected CartService $cartService;
    protected ProductService $productService;
    protected $model;

    public function __construct(CartService $cartService, ProductService $productService)
    {
        $this->cartService = $cartService;
        $this->productService = $productService;
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
        return $cart
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

    // add product to cart
    public function addToCart(AddToCartRequest $request, $product_id)
    {
        $product = $this->productService->checkIfProductExist($product_id);
        if($product) {
            $product_has_enough_quantity = $this->productService->checkQuantity($request->quantity, $product);

            return $product_has_enough_quantity
                ? $this->cartService->addToCart($product, $request->quantity)
                : $this->error(null, 'Product doesn\'t have enough quantity', 400);
        }

        return $this->error(null, 'Product doesn\'t exist', 404);
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
