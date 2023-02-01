<?php

namespace App\Services;

use App\Models\Category;
use App\Traits\HttpResponses;
use Illuminate\Database\Eloquent\Model;

class CategoryService extends BaseServices
{
    use HttpResponses;

    public function model(): Model
    {
        return new Category();
    }

    /**
     * Retrieve all Categories.
     *
     * @return \Illuminate\Support\Collection
     */
    public function retrieve()
    {
        return $this->model()->all();
    }

    /**
     * Create a new Category.
     *
     * @param array $data
     * @return Category
     */
    public function create(array $data)
    {
        return $this->model()->create($data);
    }

    /**
     * Update an existing Category.
     *
     * @param array $data
     * @param model $category
     * @return Category
     */
    public function update(array $data, $category)
    {
        $updateCategory = $this->model()->findOrFail($category->id);

        $updateCategory->update($data);

        return $updateCategory;
    }

    /**
     * Show a Category by ID.
     *
     * @param model $category
     * @return Category
     */
    public function show($category)
    {
        return $this->model()->findOrFail($category->id);
    }

    /**
     * Delete a Category by ID.
     *
     * @param model $category
     * @return bool
     */
    public function delete($category)
    {
        $this->model()->findOrFail($category->id)->delete();

        return $this->success(null, "Category Deleted Successfully", 204);
    }
}
