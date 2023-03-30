<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportCsvRequest;
use Illuminate\Http\Request;
use App\Http\Resources\ProductsResource;
use App\Jobs\ProductsImportCsvJob;
use App\Models\Product;
use App\Services\ProductService;
use App\Traits\HttpResponses;
Use App\Permissions\Permission;

class ProductController extends Controller
{
    use HttpResponses;

    protected ProductService $productService;
    protected $model;

    public function __construct(ProductService $productService)
    {
        $this->middleware('permission:'.Permission::LIST_PRODUCTS)->only('index', 'show', 'show');
        $this->middleware('permission:'.Permission::CREATE_PRODUCTS)->only('store');
        $this->middleware('permission:'.Permission::UPDATE_PRODUCTS)->only('update');
        $this->middleware('permission:'.Permission::DELETE_PRODUCTS)->only('destroy');
        $this->middleware('permission:'.Permission::IMPORT_CSV_PRODUCTS)->only('importCsvFile');

        $this->productService = $productService;
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
            ? $this->success(ProductsResource::collection($products), 'products list')
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

    /**
     * search for name, price, date and category's name
     * @param $query
     */
    public function search($query)
    {
        return $this->productService->search($query);
    }

    //import csv file
    public function importCsvFile(ImportCsvRequest $request) {
        $this->productService->importCsvFile($request->file);
        return $this->productService->retrieve($this->model);
    }
}
