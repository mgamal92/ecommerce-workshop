<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\CategoriesResource;
use App\Http\Resources\CategoryProductsResource;
use App\Models\Category;
use App\Services\CategoryService;
use App\Traits\HttpResponses;
Use App\Permissions\Permission;

class CategoryController extends Controller
{
    use HttpResponses;

    protected CategoryService $categoryService;
    protected $model;

    public function __construct(CategoryService $categoryService)
    {
        $this->middleware('permission:'.Permission::LIST_CATEGORIES)->only('index', 'show', 'showWithProducts');
        $this->middleware('permission:'.Permission::CREATE_CATEGORIES)->only('store');
        $this->middleware('permission:'.Permission::UPDATE_CATEGORIES)->only('update');
        $this->middleware('permission:'.Permission::DELETE_CATEGORIES)->only('destroy');

        $this->categoryService = $categoryService;
        $this->model = new Category();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return all categories
        return CategoriesResource::collection($this->categoryService->retrieve($this->model));
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
        $category = $this->categoryService->store($this->model, $request->toArray());

        return new CategoriesResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $updateCategory = $this->categoryService->update($this->model, $category->id, $request->toArray());

        return new CategoriesResource($updateCategory);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return new CategoriesResource($this->categoryService->show($this->model, $category->id));
    }

    //show single category with it's products
    public function showWithProducts($category_id)
    {
        $category = $this->categoryService->showWith($this->model, $category_id, 'products');

        return $category
            ? $this->success(new CategoryProductsResource($category), 'category data with it\'s products')
            : $this->error(null, 'category not found', 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $deleteCategory = $this->categoryService->delete($this->model, $category->id);

        if (!$deleteCategory) {
            return $this->success(null, "Category Deleted Successfully", 200);
        }
    }
}
