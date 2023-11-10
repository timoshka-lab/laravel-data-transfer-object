<?php

namespace TimoshkaLab\DataTransferObject\Tests\Unit;

use TimoshkaLab\DataTransferObject\Factory;
use TimoshkaLab\DataTransferObject\Tests\Helpers\PlainValueStaticConstructor;
use TimoshkaLab\DataTransferObject\Tests\TestCase;

final class FactoryCreationTest extends TestCase
{
    /**
     * @return void
     */
    public function test_valid_instance_creation()
    {
        $this->assertInstanceOf(Factory::class, Factory::create(self::class));
    }

    /**
     * @return void
     */
    public function test_invalid_instance_creation()
    {
        $this->expectException(\InvalidArgumentException::class);
        Factory::create('InvalidClass');
    }

    /**
     * @return void
     */
    public function test_instance_creation_with_valid_constructor()
    {
        $dto = Factory::create(PlainValueStaticConstructor::class)
            ->value('value')
            ->build(constructor: 'create');
        $this->assertInstanceOf(PlainValueStaticConstructor::class, $dto);
    }

    /**
     * @return void
     */
    public function test_instance_creation_with_invalid_constructor()
    {
        Factory::forceDefaultConstructor();
        $this->expectException(\InvalidArgumentException::class);
        Factory::create(PlainValueStaticConstructor::class)
            ->value('value')
            ->build(constructor: 'invalidConstructor');
        Factory::forceDefaultConstructor(false);
    }

    /**
     * @return void
     */
    public function test_invalid_static_method()
    {
        $this->expectException(\BadMethodCallException::class);
        Factory::invalidStaticMethod();
    }
}