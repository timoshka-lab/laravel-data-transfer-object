<?php

namespace TimoshkaLab\DataTransferObject\Params;

use Closure;
use TimoshkaLab\DataTransferObject\Builder;

trait InstanceMapper
{
    /**
     * @param string|Closure $mapTo
     * @param string|null $constructor
     * @return Closure
     */
    protected static function createMapperClosure(string|Closure $mapTo, string $constructor = null): Closure
    {
        if (is_string($mapTo) && !class_exists($mapTo)) {
            throw new \InvalidArgumentException('$mapTo argument must be callable.');
        }

        if (is_a($mapTo, Closure::class) && $constructor !== null) {
            throw new \InvalidArgumentException('You can not use $constructor argument with Closure $mapTo.');
        }

        return is_string($mapTo) ? function (mixed $value) use ($mapTo, $constructor) {
            return Builder::buildInstance($mapTo, [$value], $constructor);
        } : $mapTo;
    }

    /**
     * @param mixed $value
     * @param Closure|null $mapTo
     * @return mixed
     */
    protected static function mapValue(mixed $value, Closure $mapTo = null): mixed
    {
        return $mapTo ? $mapTo($value) : $value;
    }
}
