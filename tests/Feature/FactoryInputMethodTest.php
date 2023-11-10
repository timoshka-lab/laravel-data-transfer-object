<?php

namespace TimoshkaLab\DataTransferObject\Tests\Feature;

use TimoshkaLab\DataTransferObject\Factory;
use TimoshkaLab\DataTransferObject\Tests\Helpers\CreatesRequest;
use TimoshkaLab\DataTransferObject\Tests\Helpers\ObjectValue;
use TimoshkaLab\DataTransferObject\Tests\Helpers\PlainValue;
use TimoshkaLab\DataTransferObject\Tests\Helpers\PlainValueStaticConstructor;
use TimoshkaLab\DataTransferObject\Tests\TestCase;

final class FactoryInputMethodTest extends TestCase
{
    use CreatesRequest;

    /**
     * @return void
     */
    public function test_valid_key()
    {
        $this->setUpRequest('/?key=value');
        $dto = Factory::create(PlainValue::class)->input('key')->build();
        $this->assertInstanceOf(PlainValue::class, $dto);
        $this->assertEquals($dto->getValue(), 'value');
    }

    /**
     * @return void
     */
    public function test_invalid_key()
    {
        $this->expectException(\TypeError::class);
        Factory::create(PlainValue::class)->input('invalid')->build();
    }

    /**
     * @return void
     */
    public function test_valid_string_map_to()
    {
        $this->setUpRequest('/?key=value');
        $dto = Factory::create(ObjectValue::class)->input('key', PlainValue::class)->build();
        $this->assertInstanceOf(ObjectValue::class, $dto);
        $this->assertEquals($dto->getValue()->getValue(), 'value');
    }

    /**
     * @return void
     */
    public function test_invalid_string_map_to()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->setUpRequest('/?key=value');
        Factory::create(ObjectValue::class)->input('key', 'InvalidClass')->build();
    }

    /**
     * @return void
     */
    public function test_valid_closure_map_to()
    {
        $this->setUpRequest('/?key=value');
        $dto = Factory::create(ObjectValue::class)->input('key', function (mixed $value) {
            $this->assertEquals($value, 'value');
            return new PlainValue('formatted-value');
        })->build();
        $this->assertInstanceOf(ObjectValue::class, $dto);
        $this->assertEquals($dto->getValue()->getValue(), 'formatted-value');
    }

    /**
     * @return void
     */
    public function test_closure_map_to_with_constructor()
    {
        $this->setUpRequest('/?key=value');
        $this->expectException(\InvalidArgumentException::class);

        Factory::create(PlainValueStaticConstructor::class)->input('key', function (mixed $value) {
            return $value;
        }, constructor: 'create')->build();
    }

    /**
     * @return void
     */
    public function test_default_value()
    {
        $dto = Factory::create(PlainValue::class)->input('invalid', default: 'default-value')->build();
        $this->assertInstanceOf(PlainValue::class, $dto);
        $this->assertEquals($dto->getValue(), 'default-value');
    }

    /**
     * @return void
     */
    public function test_default_value_with_closure_map_to()
    {
        $dto = Factory::create(PlainValue::class)->input('invalid', function ($value) {
            $this->assertEquals($value, 'default-value');
            return $value;
        },  default: 'default-value')->build();
        $this->assertInstanceOf(PlainValue::class, $dto);
    }

    /**
     * @return void
     */
    public function test_valid_constructor()
    {
        $this->setUpRequest('/?key=value');
        $dto = Factory::create(ObjectValue::class)->input('key', PlainValueStaticConstructor::class, constructor: 'create')->build();
        $this->assertInstanceOf(ObjectValue::class, $dto);
    }

    /**
     * @return void
     */
    public function test_invalid_constructor()
    {
        $this->expectException(\Error::class);
        $this->setUpRequest('/?key=value');
        Factory::create(ObjectValue::class)->input('key', PlainValueStaticConstructor::class, constructor: 'make')->build();
    }
}
