<?php

namespace App\Http\Controllers;

use App\Contracts\ResourcesFlow;
use App\Custom\Admin\ProductFlow as AdminProductResources;
use App\Custom\Editor\ProductFlow as EditorProductResources;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\ProductService;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    use HttpResponses;

    protected ProductService $productService;
    protected $model;
    protected $resource;
    protected $user;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
        $this->model = new Product;

        //inject container interface into constructor 
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();

            $flowClass = $this->user->hasRole('admin') ? AdminProductResources::class : EditorProductResources::class;

            app()->bind(ResourcesFlow::class, $flowClass);

            $this->resource = app()->make(ResourcesFlow::class);

            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->resource->index();
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
        return $this->resource->store($request);
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
        return $this->resource->update($request, $product);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return $this->resource->show($product);
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

    /**
     * search for name, price, date and category's name
     * @param $query
     */
    public function search($query)
    {
        return $this->productService->search($query);
    }
}
