<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class CategoryService extends BaseServices
{
    public function index()
    {
        $category = new Category;        
        $categories = $category::withCount('products')->get();
        return $categories;
    }
}
