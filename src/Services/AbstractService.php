<?php

namespace SiDev\LaravelLayered\Services;

use SiDev\LaravelLayered\Contracts\Repositories\RepositoryInterface;
use SiDev\LaravelLayered\Contracts\Services\ServiceInterface;

abstract class AbstractService implements ServiceInterface
{
    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * AbstractService constructor.
     *
     * @param RepositoryInterface $repository
     */
    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get collection of the resources.
     *
     * @return mixed
     */
    public function get()
    {
        return $this->repository->get();
    }

    /**
     * Store a newly created resource.
     *
     * @param array $attributes
     *
     * @return mixed
     */
    public function create(array $attributes)
    {
        return $this->repository->create($attributes);
    }

    /**
     * Update the specified resource.
     *
     * @param mixed $id
     * @param array $attributes
     *
     * @return mixed
     */
    public function update($id, array $attributes)
    {
        return $this->repository->update($id, $attributes);
    }

    /**
     * Destroy the specified resource.
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function delete($id)
    {
        $this->repository->delete($id);
    }
}
