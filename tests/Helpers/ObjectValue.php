<?php

namespace TimoshkaLab\DataTransferObject\Tests\Helpers;
final class ObjectValue
{
    /**
     * @var PlainValueInterface
     */
    private PlainValueInterface $value;

    /**
     * @param PlainValueInterface $value
     */
    public function __construct(PlainValueInterface $value)
    {
        $this->value = $value;
    }

    /**
     * @return PlainValueInterface
     */
    public function getValue(): PlainValueInterface
    {
        return $this->value;
    }
}