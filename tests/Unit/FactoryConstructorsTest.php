<?php

namespace TimoshkaLab\DataTransferObject\Tests\Unit;

use TimoshkaLab\DataTransferObject\Factory;
use TimoshkaLab\DataTransferObject\Tests\Helpers\ObjectValue;
use TimoshkaLab\DataTransferObject\Tests\Helpers\PlainValueStaticConstructor;
use TimoshkaLab\DataTransferObject\Tests\TestCase;

final class FactoryConstructorsTest extends TestCase
{
    /**
     * @return void
     */
    public function test_global_constructors()
    {
        Factory::useConstructors(['create'], true);

        $dto = Factory::create(PlainValueStaticConstructor::class)
            ->value('value')
            ->build();

        $this->assertInstanceOf(PlainValueStaticConstructor::class, $dto);
    }

    /**
     * @return void
     */
    public function test_global_constructors_with_priority()
    {
        Factory::useConstructors([
            10 => 'create',
            20 => 'createWithPrefix'
        ], true);

        $dto = Factory::create(PlainValueStaticConstructor::class)
            ->value('value')
            ->build();

        $this->assertInstanceOf(PlainValueStaticConstructor::class, $dto);
        $this->assertEquals($dto->getValue(), 'prefixed-value');
    }

    /**
     * @return void
     */
    public function test_limited_constructors()
    {
        Factory::useConstructors([
            PlainValueStaticConstructor::class => 'createWithPrefix'
        ], true);

        $dto = Factory::create(PlainValueStaticConstructor::class)
            ->value('value')
            ->build();

        $this->assertInstanceOf(PlainValueStaticConstructor::class, $dto);
        $this->assertEquals($dto->getValue(), 'prefixed-value');
    }

    /**
     * @return void
     */
    public function test_valid_force_default_constructors()
    {
        Factory::useConstructors(['create'], true);
        Factory::forceDefaultConstructor();

        $dto = Factory::create(ObjectValue::class)
            ->value('value', PlainValueStaticConstructor::class, constructor: 'createWithPrefix')
            ->build();

        $this->assertInstanceOf(ObjectValue::class, $dto);
        $this->assertEquals($dto->getValue()->getValue(), 'prefixed-value');
    }

    /**
     * @return void
     */
    public function test_invalid_force_default_constructors()
    {
        Factory::useConstructors(['create'], true);
        Factory::forceDefaultConstructor();

        $this->expectException(\InvalidArgumentException::class);

        Factory::create(ObjectValue::class)
            ->value('value', PlainValueStaticConstructor::class, constructor: 'invalidConstructor')
            ->build();
    }
}