<?php

namespace TimoshkaLab\DataTransferObject;

use Illuminate\Support\Arr;

trait Instantiable
{
    /**
     * @var array
     */
    private static array $constructors = [];

    /**
     * @var array
     */
    private static array $limitedConstructors = [];

    /**
     * @var bool
     */
    private static bool $forceDefaultConstructor = false;

    /**
     * @param array $constructors
     * @param bool $reset
     * @return void
     */
    public static function useConstructors(array $constructors, bool $reset = false): void
    {
        if ($reset) {
            self::resetConstructors();
        }

        array_map(function ($className, string $methodName) {
            self::addConstructor($className, $methodName);
        }, array_keys($constructors), $constructors);

        krsort(self::$constructors);
    }

    /**
     * @param string $className
     * @param string $methodName
     * @return void
     */
    private static function addConstructor(string $className, string $methodName): void
    {
        $key = is_numeric($className) ? intval($className) : $className;

        if (is_numeric($key) && !in_array($methodName, self::$constructors)) {
            self::$constructors[$key] = $methodName;
        } else if (is_string($key)) {
            self::$limitedConstructors[$key] = $methodName;
        }
    }

    /**
     * @return void
     */
    public static function resetConstructors(): void
    {
        self::$constructors = [];
        self::$limitedConstructors = [];
    }

    /**
     * @param bool $force
     * @return void
     */
    public static function forceDefaultConstructor(bool $force = true): void
    {
        self::$forceDefaultConstructor = $force;
    }

    /**
     * @param string $className
     * @param array $properties
     * @param string|null $defaultConstructor
     * @return mixed
     */
    public static function buildInstance(string $className, array $properties, string $defaultConstructor = null): mixed
    {
        $constructor = self::determineInstanceConstructor($className, $defaultConstructor);
        return $constructor === '__construct' ? new $className(...$properties) : $className::$constructor(...$properties);
    }

    /**
     * @param string $className
     * @param string|null $default
     * @return string
     */
    protected static function determineInstanceConstructor(string $className, string $default = null): string
    {
        if (!class_exists($className))
            throw new \InvalidArgumentException(sprintf('Class %s does not exist.', $className));

        if (!is_null($default)) {
            if (method_exists($className, $default)) {
                return $default;
            }

            if (self::$forceDefaultConstructor) {
                throw new \InvalidArgumentException(sprintf('Method %s does not exist in class %s.', $default, $className));
            }
        }

        return self::$limitedConstructors[$className] ?? (Arr::first(self::$constructors, function (string $method) use ($className) {
            return method_exists($className, $method);
        }) ?: '__construct');
    }
}
