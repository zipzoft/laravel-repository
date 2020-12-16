<?php namespace Zipzoft\Repository;

abstract class Criteria
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public abstract function apply($query, RepositoryInterface $repository);
}
