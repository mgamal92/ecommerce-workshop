<?php

namespace App\Custom\Admin;

use App\Contracts\ResourcesFlow;
use App\Http\Resources\CategoriesResource;
use App\Models\Category;
use App\Services\CategoryService;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class CategoryFlow implements ResourcesFlow
{
    use HttpResponses;

    public function __construct(protected CategoryService $categoryService, protected Category $model)
    {
    }

    public function index()
    {
        return (CategoriesResource::collection($this->categoryService->retrieve($this->model)))->additional(['user-type' => 'admin']);
    }

    public function store(Request $request)
    {
        $category = $this->categoryService->store($this->model, $request->toArray());

        return (new CategoriesResource($category))->additional(['user-type' => 'admin']);
    }

    public function update(Request $request, $model)
    {
        $updateCategory = $this->categoryService->update($this->model, $model->id, $request->toArray());

        return (new CategoriesResource($updateCategory))->additional(['user-type' => 'admin']);
    }

    public function show($model)
    {
        return (new CategoriesResource($this->categoryService->show($this->model, $model->id)))->additional(['user-type' => 'admin']);
    }
}
