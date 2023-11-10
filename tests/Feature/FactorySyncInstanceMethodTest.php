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

final class FactorySyncInstanceMethodTest extends TestCase
{
    /**
     * @return void
     */
    public function test_valid_string_instance()
    {
        $dto = Factory::syncInstance(PlainValue::class, function (Builder $builder) {
            $builder->value('value');
        });

        $this->assertInstanceOf(PlainValue::class, $dto);
        $this->assertEquals($dto->getValue(), 'value');
    }

    /**
     * @return void
     */
    public function test_invalid_string_instance()
    {
        $this->expectException(\InvalidArgumentException::class);

        Factory::syncInstance('InvalidClass', function (Builder $builder) {
            $builder->value('value');
        });
    }

    /**
     * @return void
     */
    public function test_valid_closure_instance()
    {
        $dto = Factory::syncInstance(function (array $params, Builder $builder) {
            return new PlainValue('value');
        });

        $this->assertInstanceOf(PlainValue::class, $dto);
        $this->assertEquals($dto->getValue(), 'value');
    }


    /**
     * @return void
     */
    public function test_valid_constructor()
    {
        $dto = Factory::syncInstance(PlainValueStaticConstructor::class, function (Builder $builder) {
            $builder->value('value');
        }, 'createWithPrefix');

        $this->assertInstanceOf(PlainValueStaticConstructor::class, $dto);
        $this->assertEquals($dto->getValue(), 'prefixed-value');
    }

    /**
     * @return void
     */
    public function test_invalid_constructor()
    {
        Factory::forceDefaultConstructor();
        $this->expectException(\InvalidArgumentException::class);

        Factory::syncInstance(PlainValueStaticConstructor::class, function (Builder $builder) {
            $builder->value('value');
        }, 'invalidConstructor');

        Factory::forceDefaultConstructor(false);
    }

    /**
     * @return void
     */
    public function test_params_as_array()
    {
        $dto = Factory::syncInstance(Collection::class, function (Builder $builder) {
            foreach (range(1, 3) as $index) {
                $builder->value($index);
            }
        }, paramsAsArray: true);

        $this->assertInstanceOf(Collection::class, $dto);
        $this->assertEquals($dto->toArray(), [1, 2, 3]);
    }
}