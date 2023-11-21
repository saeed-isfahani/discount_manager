<?php

namespace App\Repositories;

use App\Contracts\Repositories\BaseRepositoryInterface;

use App\Exceptions\BadRequestException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;

    /**
     * @throws BadRequestException
     */
    public function __construct(public Application $app)
    {
        $this->makeModel();
    }

    abstract protected function model();

    /**
     * @throws BadRequestException
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new BadRequestException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    /**
     * all
     *
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function all(array $columns = ['*'], array $relations = []): Collection
    {
        $query = $this->model->newQuery();

        if (!empty($relations)) {
            $query = $query->with($relations);
        }

        return $query->get($columns);
    }

    /**
     * create
     *
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    /**
     * update
     *
     * @param array $attributes
     * @param int $id
     * @return Model
     */
    public function update(array $attributes, int $id): Model
    {
        $record = $this->find($id);
        $record->update($attributes);
        return $record;
    }

    /**
     * delete
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->find($id)->delete();
    }

    /**
     * find
     *
     * @param int $id
     * @param array $columns
     * @return Model|null
     */
    public function find(int $id, array $columns = ['*']): ?Model
    {
        return $this->model->find($id, $columns);
    }

    /**
     * find By
     *
     * @param string $field
     * @param mixed $value
     * @param array $columns
     * @return Model|null
     */
    public function findBy(string $field, mixed $value, array $columns = ['*']): ?Model
    {
        return $this->model->where($field, '=', $value)->first($columns);
    }

    /**
     * find Where
     *
     * @param array $where
     * @param array $columns
     * @return Collection
     */
    public function findWhere(array $where, array $columns = ['*']): Collection
    {
        $query = $this->model->newQuery();

        foreach ($where as $field => $value) {
            $query = $query->where($field, $value);
        }

        return $query->get($columns);
    }

    /**
     * find Where In
     *
     * @param string $field
     * @param array $values
     * @param string[] $columns
     * @return Collection
     */
    public function findWhereIn(string $field, array $values, array $columns = ['*']): Collection
    {
        return $this->model->whereIn($field, $values)->get($columns);
    }

    /**
     * find Where Not In
     *
     * @param string $field
     * @param array $values
     * @param array $columns
     * @return Collection
     */
    public function findWhereNotIn(string $field, array $values, array $columns = ['*']): Collection
    {
        return $this->model->whereNotIn($field, $values)->get($columns);
    }

    /**
     * paginate
     *
     * @param int $perPage
     * @param array $columns
     * @param array $where
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $columns = ['*'], array $where = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        foreach ($where as $field => $value) {
            $query = $query->where($field, $value);
        }

        return $query->paginate($perPage, $columns);
    }

    /**
     * with
     *
     * @param array $relations
     * @return Builder
     */
    public function with(array $relations): Builder
    {
        return $this->model->with($relations);
    }

    /**
     * exists
     *
     * @param string $field
     * @param mixed $value
     * @return bool
     */
    public function exists(string $field, mixed $value): bool
    {
        return $this->model->where($field, $value)->exists();
    }
}
