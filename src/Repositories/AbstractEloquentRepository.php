<?php

namespace SiDev\LaravelLayered\Repositories;

use Illuminate\Database\Eloquent\Model;
use SiDev\LaravelLayered\Contracts\Repositories\RepositoryInterface;

abstract class AbstractEloquentRepository implements RepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * AbstractRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get collection of the resource.
     *
     * @return mixed
     */
    public function get()
    {
        return $this->model->get();
    }

    /**
     * Store a newly created resource.
     *
     * @param  array  $attributes
     *
     * @return Model
     */
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * Update the specified resource.
     *
     * @param  mixed  $id
     * @param  array  $attributes
     *
     * @return Model
     */
    public function update($id, array $attributes)
    {
        $model = $this->model->findOrFail($id);

        return tap($model->update($attributes));
    }

    /**
     * Delete the specified resource.
     *
     * @param  mixed  $id
     */
    public function delete($id)
    {
        $this->model->where('id', $id)->delete();
    }

    /**
     * @param $method
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return $this->model->$method(...$arguments);
    }
}
