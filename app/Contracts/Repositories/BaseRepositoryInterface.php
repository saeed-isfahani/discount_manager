<?php

namespace App\Contracts\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    public function all(array $columns = ['*'], array $relations = []): Collection;

    public function create(array $attributes): Model;

    public function update(array $attributes, int $id): Model;

    public function delete(int $id): bool;

    public function find(int $id, array $columns = ['*']): ?Model;

    public function findBy(string $field, mixed $value, array $columns = ['*']): ?Model;

    public function findWhere(array $where, array $columns = ['*']): Collection;

    public function findWhereIn(string $field, array $values, array $columns = ['*']): Collection;

    public function findWhereNotIn(string $field, array $values, array $columns = ['*']): Collection;

    public function paginate(int $perPage = 15, array $columns = ['*'], array $where = []): LengthAwarePaginator;

    public function with(array $relations): Builder;
}
