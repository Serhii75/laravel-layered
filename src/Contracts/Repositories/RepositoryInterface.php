<?php

namespace SiDev\LaravelLayered\Contracts\Repositories;

interface RepositoryInterface
{
    /**
     * Get collection of resources.
     *
     * @param  array|mixed  $columns
     *
     * @return  mixed
     */
    public function get($columns = ['*']);

    /**
     * Find a resource by its primary key.
     *
     * @param  mixed  $id
     * @param  array  $columns
     *
     * @return mixed
     */
    public function find($id, array $columns = ['*']);

    /**
     * Get collection of resources by the specified condition(s).
     *
     * @param  array  $where
     * @param  array  $columns
     *
     * @return mixed
     */
    public function findWhere(array $where, array $columns = ['*']);

    /**
     * Get a resource by the specified condition(s).
     *
     * @param  array  $where
     * @param  array  $columns
     *
     * @return mixed
     */
    public function firstWhere(array $where, $columns = ['*']);

    /**
     * Store a newly created resource.
     *
     * @param  array $attributes
     *
     * @return  mixed
     */
    public function create(array $attributes);

    /**
     * Update the specified resource.
     *
     * @param  mixed  $id
     * @param  array  $attributes
     *
     * @return mixed
     */
    public function update($id, array $attributes);

    /**
     * Update resources by the specified condition(s).
     *
     * @param  array  $where
     * @param  array  $attributes
     *
     * @return mixed
     */
    public function updateWhere(array $where, array $attributes);

    /**
     * Delete the specified resource.
     *
     * @param  mixed  $id
     *
     * @return  mixed
     */
    public function delete($id);

    /**
     * Delete resources by the specified condition(s).
     *
     * @param  array  $where
     *
     * @return mixed
     */
    public function deleteWhere(array $where);
}
