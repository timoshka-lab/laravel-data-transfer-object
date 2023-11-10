<?php

namespace TimoshkaLab\DataTransferObject;

/**
 * @mixin Builder
 */
final class Factory
{
    /**
     * @var Builder
     */
    private Builder $builder;

    /**
     * @param string $instance
     */
    private function __construct(string $instance)
    {
        $this->builder = Builder::create($instance);
    }

    /**
     * @param string $instance
     * @return self
     */
    public static function create(string $instance): self
    {
        return new self($instance);
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return Builder
     */
    public function __call(string $name, array $arguments): Builder
    {
        return $this->builder->{$name}(...$arguments);
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic(string $name, array $arguments): mixed
    {
        return Builder::{$name}(...$arguments);
    }
}
