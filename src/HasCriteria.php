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
     * @param Criteria $criteria
     * @return static
     */
    public function pushCriteria(Criteria $criteria);

    /**
     * @param bool|mixed $value
     * @param Criteria $criteria
     * @return static
     */
    public function pushCriteriaWhen($value, Criteria $criteria);

    /**
     * @return static
     */
    public function applyCriteria();
}
