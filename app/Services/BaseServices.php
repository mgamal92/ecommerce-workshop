<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

abstract class BaseServices
{

    private function model($model)
    {
        return $model;
    }
    /**
     * Retrieve all records.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function retrieve($model)
    {
        return $this->model($model)->all();
    }

    /**
     * Create a new record.
     * @param model $model
     * @param array $data
     * @return Model
     */
    protected function create($model, array $data)
    {
        return $this->model($model)->create($data);
    }

    /**
     * Update an existing record.
     * @param model $model
     * @param array $data
     * @param int $id
     * @return Model
     */
    protected function update($model, int $id, array $data)
    {
        $category = $this->model($model)->findOrFail($id);

        $category->update($data);

        return $category;
    }

    /**
     * Show a record by ID.
     * @param model $model
     * @param int $id
     * @return Model
     */
    protected function show($model, $id)
    {
        return $this->model($model)->findOrFail($id);
    }

    /**
     * Delete a record by ID.
     * @param model $model
     * @param int $id
     * @return bool
     */
    protected function delete($model, $id)
    {
        $this->model($model)->findOrFail($id)->delete();
    }
}
