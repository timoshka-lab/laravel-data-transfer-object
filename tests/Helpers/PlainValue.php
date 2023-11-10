<?php

namespace TimoshkaLab\DataTransferObject\Tests\Helpers;
final class PlainValue implements PlainValueInterface
{
    /**
     * @var string
     */
    private string $value;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}