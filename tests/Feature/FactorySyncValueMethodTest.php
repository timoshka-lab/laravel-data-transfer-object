<?php

namespace TimoshkaLab\DataTransferObject\Tests\Feature;

use TimoshkaLab\DataTransferObject\Factory;
use TimoshkaLab\DataTransferObject\Tests\Helpers\PlainValue;
use TimoshkaLab\DataTransferObject\Tests\Helpers\PlainValueStaticConstructor;
use TimoshkaLab\DataTransferObject\Tests\TestCase;

final class FactorySyncValueMethodTest extends TestCase
{
    /**
     * @return void
     */
    public function test_valid_value()
    {
        $this->assertEquals(Factory::syncValue('value'), 'value');
    }


    /**
     * @return void
     */
    public function test_valid_string_map_to()
    {
        $dto = Factory::syncValue('value', PlainValue::class);
        $this->assertInstanceOf(PlainValue::class, $dto);
    }

    /**
     * @return void
     */
    public function test_invalid_string_map_to()
    {
        $this->expectException(\InvalidArgumentException::class);
        Factory::syncValue('value', 'InvalidClass');
    }

    /**
     * @return void
     */
    public function test_valid_closure_map_to()
    {
        $dto = Factory::syncValue('value', function (mixed $value) {
            $this->assertEquals($value, 'value');
            return new PlainValue('test');
        });

        $this->assertInstanceOf(PlainValue::class, $dto);
    }

    /**
     * @return void
     */
    public function test_valid_constructor()
    {
        $dto = Factory::syncValue('value', PlainValueStaticConstructor::class, constructor: 'create');
        $this->assertInstanceOf(PlainValueStaticConstructor::class, $dto);
    }

    /**
     * @return void
     */
    public function test_invalid_constructor()
    {
        $this->expectException(\Error::class);
        Factory::syncValue('value', PlainValueStaticConstructor::class, constructor: 'make');
    }
}