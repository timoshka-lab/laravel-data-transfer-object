<?php

namespace TimoshkaLab\DataTransferObject\Tests\Helpers;
final class PlainValueStaticConstructor implements PlainValueInterface
{
    /**
     * @var string
     */
    private string $value;

    /**
     * @param string $value
     */
    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @param string $value
     * @return self
     */
    public static function create(string $value): self
    {
        return new self($value);
    }

    /**
     * @param string $value
     * @return self
     */
    public static function createWithPrefix(string $value): self
    {
        return new self('prefixed-' . $value);
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}