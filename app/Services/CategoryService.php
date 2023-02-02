<?php

namespace App\Services;

use App\Http\Resources\CategoriesResource;
use App\Models\Category;
use App\Traits\HttpResponses;
use Illuminate\Database\Eloquent\Model;

class CategoryService extends BaseServices
{
    use HttpResponses;

    /**
     * Retrieve all Categories.
     *
     * @return \Illuminate\Support\Collection
     */
    public function index()
    {
        $categories = $this->retrieve(new Category());

        return $categories;
    }

    /**
     * Create a new Category.
     *
     * @param array $data
     * @return Category
     */
    public function storeCategory(array $data)
    {
        $category = $this->store(new Category(), $data);

        return new CategoriesResource($category);
    }

    /**
     * Update an existing Category.
     *
     * @param array $data
     * @param model $category
     * @return Category
     */
    public function updateCategory(array $data, $category)
    {
        $updateCategory = $this->update(new Category(), $category->id, $data);

        return new CategoriesResource($updateCategory);
    }

    /**
     * Show a Category by ID.
     *
     * @param model $category
     * @return Category
     */
    public function showCategory($category)
    {
        return new CategoriesResource($this->show(new Category(), $category->id));
    }

    /**
     * Delete a Category by ID.
     *
     * @param model $category
     * @return bool
     */
    public function destroy($category)
    {
        $deleteCategory = $this->delete(new Category(), $category->id);

        if (!$deleteCategory) {
            return $this->success(null, "Category Deleted Successfully", 200);
        }
    }
}
