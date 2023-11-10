<?php

namespace TimoshkaLab\DataTransferObject\Params;

use Closure;

final class ValueParam implements ParamInterface
{
    use InstanceMapper;

    /**
     * @var mixed
     */
    private mixed $value;

    /**
     * @var Closure|null
     */
    private ?Closure $mapTo;

    /**
     * @param mixed $value
     * @param Closure|null $mapTo
     */
    private function __construct(mixed $value, ?Closure $mapTo)
    {
        $this->value = $value;
        $this->mapTo = $mapTo;
    }

    /**
     * @param mixed $value
     * @param string|Closure|null $mapTo
     * @param string|null $constructor
     * @return self
     */
    public static function create(mixed $value, string|Closure $mapTo = null, string $constructor = null): self
    {
        $callback = is_null($mapTo) ? null : self::createMapperClosure($mapTo, $constructor);
        return new self($value, $callback);
    }

    /**
     * @param array $input
     * @return mixed
     */
    public function build(array $input): mixed
    {
        return self::mapValue($this->value, $this->mapTo);
    }
}
