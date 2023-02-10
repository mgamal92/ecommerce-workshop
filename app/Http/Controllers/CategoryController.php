<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoriesResource;
use App\Models\Category;
use App\Services\CategoryService;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use HttpResponses;

    protected CategoryService $categoryService;

    protected $model;

    public function __construct(CategoryService $categoryService)
    {
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
        //return all categories with "products count belongs to each category"

        return CategoriesResource::collection($this->categoryService->index());
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
