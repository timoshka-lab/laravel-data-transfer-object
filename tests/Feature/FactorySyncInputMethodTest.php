<?php

namespace TimoshkaLab\DataTransferObject\Tests\Feature;

use TimoshkaLab\DataTransferObject\Factory;
use TimoshkaLab\DataTransferObject\Tests\Helpers\CreatesRequest;
use TimoshkaLab\DataTransferObject\Tests\Helpers\ObjectValue;
use TimoshkaLab\DataTransferObject\Tests\Helpers\PlainValue;
use TimoshkaLab\DataTransferObject\Tests\Helpers\PlainValueStaticConstructor;
use TimoshkaLab\DataTransferObject\Tests\TestCase;

final class FactorySyncInputMethodTest extends TestCase
{
    use CreatesRequest;

    /**
     * @return void
     */
    public function test_valid_key()
    {
        $this->setUpRequest('/?key=value');
        $this->assertEquals(Factory::syncInput('key'), 'value');
    }

    /**
     * @return void
     */
    public function test_invalid_key()
    {
        $this->expectException(\TypeError::class);
        Factory::syncInput('invalid', PlainValue::class);
    }


    /**
     * @return void
     */
    public function test_valid_string_map_to()
    {
        $this->setUpRequest('/?key=value');
        $dto = Factory::syncInput('key', PlainValue::class);
        $this->assertInstanceOf(PlainValue::class, $dto);
        $this->assertEquals($dto->getValue(), 'value');
    }


    /**
     * @return void
     */
    public function test_invalid_string_map_to()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->setUpRequest('/?key=value');
        Factory::syncInput('key', 'InvalidClass');
    }


    /**
     * @return void
     */
    public function test_valid_closure_map_to()
    {
        $this->setUpRequest('/?key=value');
        $dto = Factory::syncInput('key', function (mixed $value) {
            $this->assertEquals($value, 'value');
            return new PlainValue('formatted-value');
        });
        $this->assertInstanceOf(PlainValue::class, $dto);
        $this->assertEquals($dto->getValue(), 'formatted-value');
    }

    /**
     * @return void
     */
    public function test_valid_default_value()
    {
        $dto = Factory::syncInput('invalid', default: 'default-value');
        $this->assertEquals($dto, 'default-value');
    }

    /**
     * @return void
     */
    public function test_valid_constructor()
    {
        $this->setUpRequest('/?key=value');
        $dto = Factory::syncInput('key', PlainValueStaticConstructor::class, constructor: 'create');
        $this->assertInstanceOf(PlainValueStaticConstructor::class, $dto);
        $this->assertEquals($dto->getValue(), 'value');
    }

    /**
     * @return void
     */
    public function test_invalid_constructor()
    {
        $this->expectException(\Error::class);
        $this->setUpRequest('/?key=value');
        Factory::syncInput('key', PlainValueStaticConstructor::class, constructor: 'make');
    }
}
