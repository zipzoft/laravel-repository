<?php namespace Zipzoft\Repository;


interface HasCriteria
{
    /**
     * @param bool $status
     * @return static
     */
    public function skipCriteria($status = true);

    /**
     * @return mixed
     */
    public function getCriteria();

    /**
     * @param Criteria $criteria
     * @return static
     */
    public function getByCriteria(Criteria $criteria);

    /**
     * @param Criteria|\Closure $criteria
     * @return static
     */
    public function pushCriteria($criteria);

    /**
     * @param bool|mixed $value
     * @param Criteria|\Closure $criteria
     * @return static
     */
    public function pushCriteriaWhen($value, $criteria);

    /**
     * @return static
     */
    public function applyCriteria();
}
