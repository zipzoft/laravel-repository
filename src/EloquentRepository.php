<?php namespace Zipzoft\Repository;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use InvalidArgumentException;

abstract class EloquentRepository implements RepositoryInterface, HasCriteria
{
    /**
     * @var \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * @var Collection
     */
    protected $criteria;

    /**
     * @var bool
     */
    protected $skipCriteria = false;

    /**
     * @var Application
     */
    protected $app;

    /**
     * @return string
     */
    abstract protected function getModelIdentifier(): string;

    /**
     * @param Application $app
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->criteria = new Collection();

        $this->resetScope();
        $this->model = $this->newModelQueryInstance();
    }


    /**
     * @param string[] $columns
     * @return Collection
     */
    public function all($columns = ['*']): Collection
    {
        return $this->model->get($columns);
    }

    /**
     * @param string[] $columns
     * @return Collection
     */
    public function get($columns = ['*']): Collection
    {
        $this->applyCriteria();

        return $this->model->get($columns);
    }

    /**
     * @param int $perPage
     * @param array $columns
     * @return LengthAwarePaginator
     */
    public function paginate($perPage = 15, $columns = ['*']): LengthAwarePaginator
    {
        $this->applyCriteria();

        return $this->model->paginate($perPage, $columns);
    }

    /**
     * @param $key
     * @param array $columns
     * @return mixed
     */
    public function find($key, $columns = ['*'])
    {
        $this->applyCriteria();

        if ($key instanceof Model) {
            $key = $key->{$this->getKeyName()};
        }

        return $this->findBy($this->getKeyName(), $key, $columns);
    }

    /**
     * @param $keyName
     * @param $value
     * @param string[] $columns
     * @return \Illuminate\Database\Eloquent\Builder|Model|object|null
     */
    public function findBy($keyName, $value = null, $columns = ['*'])
    {
        $this->applyCriteria();

        return $this->model->where($keyName, '=', $value)->first($columns);
    }

    /**
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function save(array $data = []): bool
    {
        return $this->model->fill($data)->save();
    }

    /**
     * @param array $data
     * @param $key
     * @param null $keyName
     * @return mixed
     */
    public function update(array $data, $key, $keyName = null)
    {
        if (is_null($keyName)) {
            $keyName = $this->getKeyName();
        }

        return $this->model->where($keyName, '=', $key)->update($data);
    }

    /**
     * @param $key
     * @param null $keyName
     * @return mixed
     */
    public function delete($key, $keyName = null)
    {
        if (is_null($keyName)) {
            $keyName = $this->getKeyName();
        }

        return $this->model->where($keyName, $key)->delete();
    }

    /**
     * @param string $key
     * @return mixed|void
     */
    public function sum(string $key)
    {
        $this->applyCriteria();

        return $this->model->sum($key);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function newModelQueryInstance()
    {
        $model = $this->app->make($this->getModelIdentifier());

        if ($model instanceof Model) {
            return $model;
        }

        throw new InvalidArgumentException("Class {$this->getModelIdentifier()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
    }

    /**
     * @return string
     */
    public function getKeyName()
    {
        return $this->model->getModel()->getKeyName();
    }

    /**
     * @return $this
     */
    public function resetScope()
    {
        return $this->skipCriteria(false);
    }

    /**
     * @param bool $status
     * @return $this
     */
    public function skipCriteria($status = true)
    {
        $this->skipCriteria = $status;

        return $this;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * @param Criteria $criteria
     * @return $this
     */
    public function getByCriteria(Criteria $criteria)
    {
        $this->model = $criteria->apply($this->model, $this);

        return $this;
    }

    /**
     * @param Criteria $criteria
     * @return $this
     */
    public function pushCriteria(Criteria $criteria)
    {
        $this->criteria->push($criteria);

        return $this;
    }

    /**
     * @param bool|mixed $value
     * @param Criteria $criteria
     * @return $this
     */
    public function pushCriteriaWhen($value, Criteria $criteria)
    {
        if ($value) {
            return $this->pushCriteria($criteria);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function applyCriteria()
    {
        if ($this->skipCriteria === true) {
            return $this;
        }

        foreach ($this->getCriteria() as $criteria) {
            if ($criteria instanceof Criteria) {
                $this->model = $criteria->apply($this->model, $this);
            }
        }

        return $this;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder|Model $model
     * @return $this
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }
}
