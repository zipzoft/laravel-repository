<?php namespace Zipzoft\Repository;

use Zipzoft\Repository\Expression\SkipCriteria;
use Zipzoft\Repository\Expression\StopCriteria;

abstract class Criteria
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public abstract function apply($query, RepositoryInterface $repository);

    /**
     * @return SkipCriteria
     */
    protected function skip()
    {
        return new SkipCriteria();
    }

    /**
     * @param $query
     * @return StopCriteria
     */
    protected function stop($query)
    {
        return new StopCriteria($query);
    }
}
