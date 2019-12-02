<?php

namespace SiDev\LaravelLayered\Contracts;

use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    /**
     * Get collection of the resource.
     *
     * @return  mixed
     */
    public function get();

    /**
     * Store a newly created resource.
     *
     * @param  array $attributes
     *
     * @return  Model
     */
    public function create(array $attributes);

    /**
     * Update the specified resource.
     *
     * @param  mixed  $id
     * @param  array  $attributes
     *
     * @return Model
     */
    public function update($id, array $attributes);

    /**
     * Delete the specified resource.
     *
     * @param  mixed  $id
     *
     * @return  mixed
     */
    public function delete($id);
}
