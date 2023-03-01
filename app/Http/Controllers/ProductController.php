<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductsResource;
use App\Models\Product;
use App\Services\CartService;
use App\Services\ProductService;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use HttpResponses;

    protected ProductService $productService;
    protected CartService $cartService;

    protected $model;

    public function __construct(ProductService $productService, CartService $cartService)
    {
        $this->productService = $productService;
        $this->cartService = $cartService;
        $this->model = new Product;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = $this->productService->getAllProducts($this->model);
        return count($products) > 0
            ? $this->success(ProductsResource::collection($products),'products list')
            : $this->error(null, 'No Products Found', 404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //NOTE no validations applied
        $product = $this->productService->store($this->model, $request->toArray());

        return new ProductsResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $updateProduct = $this->productService->update($this->model, $product->id, $request->toArray());

        return new ProductsResource($updateProduct);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return new ProductsResource($this->productService->show($this->model, $product->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $deleteProduct = $this->productService->delete($this->model, $product->id);

        if (!$deleteProduct) {
            return $this->success(null, "Product Deleted Successfully", 200);
        }
    }

    // add product to cart
    public function addToCart(Request $request, Product $product)
    {
        $product_has_enough_quantity = $this->productService->checkQuantity($request->quantity, $product->id);

        return $product_has_enough_quantity
            ? $this->cartService->addToCart($product, $request->quantity)
            : $this->error(null, 'Product doesn\'t have enough quantity', 404);
    }
}
