<?php

namespace TimoshkaLab\DataTransferObject\Params;

use Closure;
use Illuminate\Support\Arr;

final class InputParam implements ParamInterface
{
    use InstanceMapper;

    /**
     * @var string
     */
    private string $key;

    /**
     * @var Closure|null
     */
    private ?Closure $mapTo;

    /**
     * @var mixed
     */
    private mixed $default;

    /**
     * @param string $key
     * @param Closure|null $mapTo
     * @param mixed $default
     */
    private function __construct(string $key, Closure $mapTo = null, mixed $default = null)
    {
        $this->key = $key;
        $this->mapTo = $mapTo;
        $this->default = $default;
    }

    /**
     * @param string $key
     * @param string|Closure|null $mapTo
     * @param mixed|null $default
     * @param string|null $constructor
     * @return self
     */
    public static function create(string $key, string|Closure $mapTo = null, mixed $default = null, string $constructor = null): self
    {
        $callback = is_null($mapTo) ? null : self::createMapperClosure($mapTo, $constructor);
        return new self($key, $callback, $default);
    }

    /**
     * @param array $input
     * @return mixed
     */
    public function build(array $input): mixed
    {
        return self::mapValue($this->getValue($input), $this->mapTo);
    }

    /**
     * @param array $input
     * @return mixed
     */
    private function getValue(array $input): mixed
    {
        return Arr::get($input, $this->key, $this->default);
    }
}
