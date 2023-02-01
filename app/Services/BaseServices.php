<?php

namespace App\Services;

abstract class BaseServices
{
    /**
     * Retrieve all records.
     *
     * @return \Illuminate\Support\Collection
     */
    abstract public function retrieve();

    /**
     * Create a new record.
     *
     * @param array $data
     * @return Model
     */
    abstract public function create(array $data);

    /**
     * Update an existing record.
     *
     * @param array $data
     * @param int $id
     * @return Model
     */
    abstract public function update(array $data, int $id);

    /**
     * Show a record by ID.
     *
     * @param int $id
     * @return Model
     */
    abstract public function show($id);

    /**
     * Delete a record by ID.
     *
     * @param int $id
     * @return bool
     */
    abstract public function delete($id);
}
