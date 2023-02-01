<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoriesResource;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{

    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        return $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return all categories
        return CategoriesResource::collection($this->categoryService->retrieve());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //NOTE no validations added
        $category = $this->categoryService->create([$request]);

        //return new category added
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
        $updateCategory =  $this->categoryService->update($request->toArray(), $category);

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
        $showCategory =  $this->categoryService->show($category);

        return new CategoriesResource($showCategory);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        return $this->categoryService->delete($category);
    }
}
