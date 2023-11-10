<?php

namespace TimoshkaLab\DataTransferObject\Params;

use TimoshkaLab\DataTransferObject\Builder;

final class InstanceParam implements ParamInterface
{
    /**
     * @var Builder
     */
    private Builder $builder;

    /**
     * @var string|null
     */
    private ?string $constructor;

    /**
     * @param Builder $builder
     * @param string|null $constructor
     */
    private function __construct(Builder $builder, string $constructor = null)
    {
        $this->builder = $builder;
        $this->constructor = $constructor;
    }

    /**
     * @param Builder $builder
     * @param string|null $constructor
     * @return self
     */
    public static function create(Builder $builder, string $constructor = null): self
    {
        return new self($builder, $constructor);
    }

    /**
     * @param array $input
     * @return mixed
     */
    public function build(array $input): mixed
    {
        return $this->builder->build($input, $this->constructor);
    }
}
