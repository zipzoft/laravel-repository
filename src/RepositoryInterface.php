<?php namespace Zipzoft\Repository;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

interface RepositoryInterface
{

    /**
     * @param string[] $columns
     * @return Collection
     */
    public function all($columns = ['*']): Collection;

    /**
     * @param string[] $columns
     * @return Collection
     */
    public function get($columns = ['*']): Collection;

    /**
     * @param int $perPage
     * @param string[] $columns
     * @return LengthAwarePaginator
     */
    public function paginate($perPage = 15, $columns = ['*']): LengthAwarePaginator;

    /**
     * @param $key
     * @param array $columns
     * @return mixed
     */
    public function find($key, $columns = ['*']);

    /**
     * @param $attribute
     * @param $value
     * @param string[] $columns
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null
     */
    public function findBy($attribute, $value, $columns = ['*']);

    /**
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data);

    /**
     * @param array $data
     * @return bool
     */
    public function save(array $data = []): bool;

    /**
     * @param array $data
     * @param $key
     * @param null $keyName
     * @return mixed
     */
    public function update(array $data, $key, $keyName = null);

    /**
     * @param $key
     * @param null $keyName
     * @return mixed
     */
    public function delete($key, $keyName = null);

    /**
     * @param string $key
     * @return mixed
     */
    public function sum(string $key);

    /**
     * @return Builder
     */
    public function createBuilder(): Builder;
}
