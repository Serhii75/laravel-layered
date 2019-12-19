<?php

namespace SiDev\LaravelLayered\Contracts\Services;

interface ServiceInterface
{
    /**
     * Get collection of resources.
     *
     * @return mixed
     */
    public function get();

    /**
     * Store a newly created resource.
     *
     * @param array $attributes
     *
     * @return mixed
     */
    public function create(array $attributes);

    /**
     * Update the specified resource.
     *
     * @param mixed  $id
     * @param array $attributes
     *
     * @return mixed
     */
    public function update($id, array $attributes);

    /**
     * Destroy the specified resource.
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function delete($id);
}
