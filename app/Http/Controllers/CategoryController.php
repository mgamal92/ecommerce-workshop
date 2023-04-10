<?php

namespace App\Http\Controllers;

use App\Contracts\ResourcesFlow;
use App\Custom\Admin\CategoryFlow as AdminCategoryResources;
use App\Custom\Editor\CategoryFlow as EditorCategoryResources;
use App\Http\Resources\CategoryProductsResource;
use App\Models\Category;
use App\Services\CategoryService;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    use HttpResponses;

    protected CategoryService $categoryService;
    protected $model;
    protected $resource;
    protected $user;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
        $this->model = new Category();

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();

            $flowClass = $this->user->hasRole('admin') ? AdminCategoryResources::class : EditorCategoryResources::class;

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
        //return all categories
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        return $this->resource->update($request, $category);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return $this->resource->show($category);
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
