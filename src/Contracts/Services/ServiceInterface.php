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
     * @param int   $id
     * @param array $attributes
     *
     * @return mixed
     */
    public function update(int $id, array $attributes);

    /**
     * Destroy the specified resource.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function delete(int $id);
}
