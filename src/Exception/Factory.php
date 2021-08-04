<?php

namespace Zipzoft\Repository\Exception;

use InvalidArgumentException;

class Factory
{
    /**
     * @param $className
     * @return InvalidArgumentException
     */
    public static function unsupportedCriteria($className)
    {
        return new InvalidArgumentException("Unsupported expression: {$className}");
    }

    /**
     * @param $className
     * @return InvalidArgumentException
     */
    public static function invalidCriteriaInstance($className)
    {
        return new InvalidArgumentException("Class {$className} must instance of Criteria");
    }

    /**
     * @param $modelName
     * @return InvalidArgumentException
     */
    public static function invalidModelName($modelName)
    {
        return new InvalidArgumentException("Class {$modelName} must be an instance of Illuminate\\Database\\Eloquent\\Model");
    }
}