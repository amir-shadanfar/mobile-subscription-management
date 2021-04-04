<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Repository implements RepositoryInterface
{
    protected $model;

    /**
     * Repository constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @return Collection|Model[]
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * @param $id
     * @return Model|null
     */
    public function find($id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * @param array $data
     * @param $id
     * @return bool
     */
    public function update(array $data, $id): bool
    {
        $record = $this->find($id);
        return $record->update($data);
    }

    /**
     * @param $id
     * @return int
     */
    public function delete($id): int
    {
        return $this->model->destroy($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id): Model
    {
        return $this->model->find($id);
    }

    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * @param $model
     * @return $this
     */
    public function setModel($model): self
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @param array $data
     * @param array $relations
     * @param false $is_paginate
     * @return LengthAwarePaginator|Builder[]|Collection
     */
    public function filter(array $data, array $relations = [], $is_paginate = false)
    {
        $query = $this->model->query();

        if (count($relations))
            $query->with($relations);

        foreach ($data as $key => $value) {
            // check column is exist
            if (Schema::hasColumn($this->model->getTable(), $key))
                // add condition
                $query->where($key, $value);
        }

        if ($is_paginate)
            return $query->paginate();
        else
            return $query->get();
    }
}
