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
     * @param array $columns
     *
     * @return mixed
     */
    public function get($columns = ['*'])
    {
        return $this->model->get($columns);
    }

    /**
     * Find a resource by its primary key.
     *
     * @param mixed $id
     * @param array $columns
     *
     * @return mixed
     */
    public function find($id, array $columns = ['*'])
    {
        return $this->model->find($id, $columns);
    }

    /**
     * Get collection of resources by the specified condition(s).
     *
     * @param array $where
     * @param array $columns
     *
     * @return mixed
     */
    public function findWhere(array $where, array $columns = ['*'])
    {
        return $this->applyWhere($where)->get($columns);
    }

    /**
     * Get a resource by the specified condition(s).
     *
     * @param array $where
     * @param array $columns
     *
     * @return mixed
     */
    public function firstWhere(array $where, $columns = ['*'])
    {
        return $this->applyWhere($where)->first($columns);
    }

    /**
     * Store a newly created resource.
     *
     * @param array $attributes
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
     * @param mixed $id
     * @param array $attributes
     *
     * @return Model
     */
    public function update($id, array $attributes)
    {
        return tap($this->model->findOrFail($id), function ($model) use ($attributes) {
            $model->update($attributes);
        });
    }

    /**
     * Update resources by the specified condition(s).
     *
     * @param array $where
     * @param array $attributes
     *
     * @return mixed
     */
    public function updateWhere(array $where, array $attributes)
    {
        $this->applyWhere($where)->update($attributes);

        return $this->applyWhere($where)->get();
    }

    /**
     * Delete the specified resource.
     *
     * @param mixed $id
     */
    public function delete($id)
    {
        $this->model->where('id', $id)->delete();
    }

    /**
     * Delete resources by the specified condition(s).
     *
     * @param array $where
     *
     * @return mixed
     */
    public function deleteWhere(array $where)
    {
        return $this->applyWhere($where)->delete();
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

    /**
     * Apply 'where' condition(s) to the model.
     *
     * @param array $where
     *
     * @return mixed
     */
    protected function applyWhere(array $where)
    {
        return $this->containsArraysOnly($where)
            ? $this->model->where($where)
            : $this->model->where(...$where);
    }

    /**
     * Cehck whether all elements are arrays.
     *
     * @param array $data
     *
     * @return bool
     */
    protected function containsArraysOnly(array $data)
    {
        foreach ($data as $datum) {
            if (!is_array($datum)) {
                return false;
            }
        }

        return true;
    }
}
