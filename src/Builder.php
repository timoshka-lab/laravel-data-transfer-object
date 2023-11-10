<?php

namespace TimoshkaLab\DataTransferObject;

use Closure;
use Illuminate\Support\Facades\Request;
use TimoshkaLab\DataTransferObject\Params\InputParam;
use TimoshkaLab\DataTransferObject\Params\InstanceParam;
use TimoshkaLab\DataTransferObject\Params\ParamInterface;
use TimoshkaLab\DataTransferObject\Params\ValueParam;

/**
 * @method static self syncInput(string $key, string|Closure|null $mapTo = null, mixed $default = null, string $constructor = null)
 * @method static self syncValue(mixed $value, string|Closure|null $mapTo = null, mixed $default = null, string $constructor = null)
 * @method static self syncInstance(string|Closure $instance, callable $callback = null, string $constructor = null, bool $paramsAsArray = false)
 */
final class Builder
{
    use Instantiable;

    /**
     * @var string|Closure
     */
    private string|Closure $instance;

    /**
     * @var array
     */
    private array $params = [];

    /**
     * @var bool
     */
    private bool $paramsAsArray;

    /**
     * @param Closure|string $instance
     * @param bool $paramsAsArray
     */
    private function __construct(string|Closure $instance, bool $paramsAsArray)
    {
        $this->instance = $instance;
        $this->paramsAsArray = $paramsAsArray;
    }

    /**
     * @param string|Closure $instance
     * @param bool $paramsAsArray
     * @return self
     */
    public static function create(string|Closure $instance, bool $paramsAsArray = false): self
    {
        if (is_string($instance) && !class_exists($instance)) {
            throw new \InvalidArgumentException("Class {$instance} does not exist.");
        }

        return new self($instance, $paramsAsArray);
    }

    /**
     * @return self
     */
    private static function createSyncBuilder(): self
    {
        return self::create(function (array $params) {
            return array_pop($params);
        });
    }

    /**
     * @param string $key
     * @param string|Closure|null $mapTo
     * @param mixed|null $default
     * @param string|null $constructor
     * @return $this
     */
    public function input(string $key, string|Closure $mapTo = null, mixed $default = null, string $constructor = null): self
    {
        $this->params[] = InputParam::create($key, $mapTo, $default, $constructor);
        return $this;
    }

    /**
     * @param mixed $value
     * @param string|Closure|null $mapTo
     * @param string|null $constructor
     * @return $this
     */
    public function value(mixed $value, string|Closure $mapTo = null, string $constructor = null): self
    {
        $this->params[] = ValueParam::create($value, $mapTo, $constructor);
        return $this;
    }

    /**
     * @param string|Closure $instance
     * @param callable|null $callback
     * @param string|null $constructor
     * @param bool $paramsAsArray
     * @return $this
     */
    public function instance(string|Closure $instance, callable $callback = null, string $constructor = null, bool $paramsAsArray = false): self
    {
        $builder = Builder::create($instance, $paramsAsArray);

        if ($callback) {
            call_user_func($callback, $builder);
        }

        $this->params[] = InstanceParam::create($builder, $constructor);
        return $this;
    }

    /**
     * @param array|null $input
     * @param string|null $constructor
     * @return mixed
     */
    public function build(array $input = null, string $constructor = null): mixed
    {
        $input = $input ?? self::captureInput();
        $params = array_map(fn(ParamInterface $param) => $param->build($input), $this->params);

        if (is_a($this->instance, Closure::class))
            return call_user_func($this->instance, $params, $this);

        if ($this->paramsAsArray) {
            $params = [$params];
        }

        return self::buildInstance($this->instance, $params, $constructor);
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic(string $name, array $arguments): mixed
    {
        static $allowedMethods = ['input', 'value', 'instance'];

        if (strpos($name, 'sync') === 0) {
            $name = lcfirst(substr($name, 4));

            if (in_array($name, $allowedMethods, true)) {
                $builder = self::createSyncBuilder();
                return $builder->{$name}(...$arguments)->build(self::captureInput());
            }
        }

        throw new \BadMethodCallException("Method {$name} does not exist.");
    }

    /**
     * @return array
     */
    private static function captureInput(): array
    {
        static $input = null;
        return $input ??= Request::all();
    }
}
