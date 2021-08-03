<?php

namespace Zipzoft\Repository\Expression;

class StopCriteria implements Expression
{

    public $query;

    /**
     * StopCriteria constructor.
     * @param $query
     */
    public function __construct($query)
    {
        $this->query = $query;
    }
}