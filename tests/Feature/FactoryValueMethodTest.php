<?php

namespace TimoshkaLab\DataTransferObject\Tests\Feature;

use TimoshkaLab\DataTransferObject\Factory;
use TimoshkaLab\DataTransferObject\Tests\Helpers\ObjectValue;
use TimoshkaLab\DataTransferObject\Tests\Helpers\PlainValue;
use TimoshkaLab\DataTransferObject\Tests\Helpers\PlainValueStaticConstructor;
use TimoshkaLab\DataTransferObject\Tests\TestCase;

final class FactoryValueMethodTest extends TestCase
{
    /**
     * @return void
     */
    public function test_valid_value()
    {
        $dto = Factory::create(PlainValue::class)->value('value')->build();
        $this->assertInstanceOf(PlainValue::class, $dto);
    }

    /**
     * @return void
     */
    public function test_invalid_value()
    {
        $this->expectException(\TypeError::class);
        Factory::create(PlainValue::class)->value(null)->build();
    }

    /**
     * @return void
     */
    public function test_valid_string_map_to()
    {
        $dto = Factory::create(ObjectValue::class)->value('value', PlainValue::class)->build();
        $this->assertInstanceOf(ObjectValue::class, $dto);
    }

    /**
     * @return void
     */
    public function test_invalid_string_map_to()
    {
        $this->expectException(\InvalidArgumentException::class);
        Factory::create(ObjectValue::class)->value('value', 'InvalidClass')->build();
    }

    /**
     * @return void
     */
    public function test_valid_closure_map_to()
    {
        $dto = Factory::create(ObjectValue::class)->value('value', function (mixed $value) {
            $this->assertEquals($value, 'value');
            return new PlainValue('test');
        })->build();
        $this->assertInstanceOf(ObjectValue::class, $dto);
    }

    /**
     * @return void
     */
    public function test_valid_constructor()
    {
        $dto = Factory::create(ObjectValue::class)->value('value', PlainValueStaticConstructor::class, constructor: 'create')->build();
        $this->assertInstanceOf(ObjectValue::class, $dto);
    }

    /**
     * @return void
     */
    public function test_invalid_constructor()
    {
        $this->expectException(\Error::class);
        Factory::create(ObjectValue::class)->value('value', PlainValueStaticConstructor::class, constructor: 'make')->build();
    }
}