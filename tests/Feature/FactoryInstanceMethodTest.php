<?php

namespace TimoshkaLab\DataTransferObject\Tests\Feature;

use Illuminate\Support\Collection;
use TimoshkaLab\DataTransferObject\Builder;
use TimoshkaLab\DataTransferObject\Factory;
use TimoshkaLab\DataTransferObject\Tests\Helpers\CollectionValue;
use TimoshkaLab\DataTransferObject\Tests\Helpers\ObjectValue;
use TimoshkaLab\DataTransferObject\Tests\Helpers\PlainValue;
use TimoshkaLab\DataTransferObject\Tests\Helpers\PlainValueStaticConstructor;
use TimoshkaLab\DataTransferObject\Tests\TestCase;

final class FactoryInstanceMethodTest extends TestCase
{
    /**
     * @return void
     */
    public function test_valid_string_instance()
    {
        $dto = Factory::create(ObjectValue::class)->instance(PlainValue::class, function (Builder $builder) {
            $builder->value('value');
        })->build();

        $this->assertInstanceOf(ObjectValue::class, $dto);
        $this->assertEquals($dto->getValue()->getValue(), 'value');
    }

    /**
     * @return void
     */
    public function test_invalid_string_instance()
    {
        $this->expectException(\InvalidArgumentException::class);

        Factory::create(ObjectValue::class)->instance('InvalidClass', function (Builder $builder) {
            $builder->value('value');
        })->build();
    }

    /**
     * @return void
     */
    public function test_valid_closure_instance()
    {
        $dto = Factory::create(ObjectValue::class)->instance(function (array $params, Builder $builder) {
            return new PlainValue('value');
        })->build();

        $this->assertInstanceOf(ObjectValue::class, $dto);
        $this->assertEquals($dto->getValue()->getValue(), 'value');
    }

    /**
     * @return void
     */
    public function test_invalid_closure_instance()
    {
        $this->expectException(\TypeError::class);

        Factory::create(ObjectValue::class)->instance(function (array $params, Builder $builder) {
            return new \stdClass();
        })->build();
    }

    /**
     * @return void
     */
    public function test_valid_constructor()
    {
        $dto = Factory::create(ObjectValue::class)->instance(PlainValueStaticConstructor::class, function (Builder $builder) {
            $builder->value('value');
        }, 'createWithPrefix')->build();

        $this->assertInstanceOf(ObjectValue::class, $dto);
        $this->assertEquals($dto->getValue()->getValue(), 'prefixed-value');
    }

    /**
     * @return void
     */
    public function test_invalid_constructor()
    {
        Factory::forceDefaultConstructor();
        $this->expectException(\InvalidArgumentException::class);

        Factory::create(ObjectValue::class)->instance(PlainValueStaticConstructor::class, function (Builder $builder) {
            $builder->value('value');
        }, 'invalidConstructor')->build();

        Factory::forceDefaultConstructor(false);
    }

    /**
     * @return void
     */
    public function test_params_as_array()
    {
        $dto = Factory::create(CollectionValue::class)->instance(Collection::class, function (Builder $builder) {
            foreach (range(1, 3) as $index) {
                $builder->value($index);
            }
        }, paramsAsArray: true)->build();

        $this->assertInstanceOf(CollectionValue::class, $dto);
        $this->assertEquals($dto->getCollection()->toArray(), [1, 2, 3]);
    }
}