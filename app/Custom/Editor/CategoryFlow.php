<?php

namespace App\Custom\Editor;

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
        return CategoriesResource::collection($this->categoryService->retrieve($this->model));
    }

    public function store(Request $request)
    {
        $category = $this->categoryService->store($this->model, $request->toArray());

        return new CategoriesResource($category);
    }

    public function update(Request $request, $model)
    {
        $updateCategory = $this->categoryService->update($this->model, $model->id, $request->toArray());

        return new CategoriesResource($updateCategory);
    }

    public function show($model)
    {
        return new CategoriesResource($this->categoryService->show($this->model, $model->id));
    }
}
