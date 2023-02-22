<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class CategoryService extends BaseServices
{
    protected $model;

    public function __construct()
    {
        $this->model = new Category();
    }

    public function showWith($model, $id, $relation)
    {
        return $this->model->with($relation)->find($id);
    }
}
