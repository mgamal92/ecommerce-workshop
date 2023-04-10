<?php

namespace App\Custom;

use App\Contracts\ResourcesFlow;
use App\Http\Resources\CategoriesResource;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;

abstract class CategoryBaseResource implements ResourcesFlow
{
    public function __construct(protected CategoryService $service, protected Category $category)
    {
    }

    public function index()
    {
        return CategoriesResource::collection($this->service->retrieve($this->category));
    }

    public function store(Request $request)
    {
        $category = $this->service->store($this->category, $request->toArray());

        return new CategoriesResource($category);
    }

    public function update(Request $request, $model)
    {
        $updateCategory = $this->service->update($this->category, $model->id, $request->toArray());

        return new CategoriesResource($updateCategory);
    }

    public function show($model)
    {
        return new CategoriesResource($this->service->show($this->category, $model->id));
    }
}
