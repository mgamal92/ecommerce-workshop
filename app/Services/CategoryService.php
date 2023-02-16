<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

class CategoryService extends BaseServices
{
    public function retrieve($model)
    {
        $categories = $model::withCount('products')->get();
        return $categories;
    }
}
