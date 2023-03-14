<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CartRequest;
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
            ? $this->success(new CartsResource($cart),'cart items list')
            : $this->error(null, 'No Cart Items Found', 404);
    }

    /*******************************
    // add product to cart
    /*******************************/
    public function addToCart(CartRequest $request, $product_id)
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

    /*******************************
    // remove product from cart
    /*******************************/
    public function removeFromCart($product_id)
    {
        $product = $this->productService->checkIfProductExist($product_id);
        if($product) {
            return $this->cartService->checkIfProductExistInCart($product->id)
                ? $this->cartService->removeFromCart($product)
                : $this->error(null, 'Product doesn\'t exist in cart', 404);
        }

        return $this->error(null, 'Product doesn\'t exist', 404);
    }

    /***************************************
    // increase or decrease product in cart
    /**************************************/
    public function updateCart(CartRequest $request, $product_id)
    {
        $product = $this->productService->checkIfProductExist($product_id);
        if($this->cartService->checkIfProductExistInCart($product->id)) {
            if($request->quantity > $this->cartService->getProductquantityInCart($product->id)) {
                $product_has_enough_quantity = $this->productService->checkQuantity($request->quantity, $product);
                return $product_has_enough_quantity
                    ? $this->cartService->increaseQuantity($product->id, $request->quantity)
                    : $this->error(null, 'Product doesn\'t have enough quantity', 404);
            }
            elseif($request->quantity < $this->cartService->getProductquantityInCart($product->id)) {
                return $this->cartService->decreaseQuantity($product->id, $request->quantity);
            }

            return $this->success(new CartsResource($this->cartService->retrieve($this->model)),'cart items list');
        }

        return $this->error(null, 'Product doesn\'t exist in cart', 404);
    }

    /*******************************
    // clear cart
    /*******************************/
    public function clear()
    {
        return $this->cartService->clearCart()
            ? $this->success(null, 'Cart deleted successfuly')
            : $this->error(null, 'Cart doesn\'t exist', 404);
    }
}
