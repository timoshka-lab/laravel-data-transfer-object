<?php

namespace TimoshkaLab\DataTransferObject\Tests\Unit;

use TimoshkaLab\DataTransferObject\Builder;
use TimoshkaLab\DataTransferObject\Tests\Helpers\PlainValue;
use TimoshkaLab\DataTransferObject\Tests\Helpers\PlainValueStaticConstructor;
use TimoshkaLab\DataTransferObject\Tests\TestCase;

final class BuilderInstanceCreationTest extends TestCase
{
    /**
     * @return void
     */
    public function test_valid_instance_build()
    {
        $instance = Builder::buildInstance(PlainValue::class, ['value']);
        $this->assertInstanceOf(PlainValue::class, $instance);
        $this->assertEquals($instance->getValue(), 'value');
    }

    /**
     * @return void
     */
    public function test_invalid_instance_build()
    {
        $this->expectException(\InvalidArgumentException::class);
        Builder::buildInstance('InvalidClass', ['value']);
    }

    /**
     * @return void
     */
    public function test_valid_default_constructor()
    {
        $instance = Builder::buildInstance(PlainValueStaticConstructor::class, ['value'], 'create');
        $this->assertInstanceOf(PlainValueStaticConstructor::class, $instance);
        $this->assertEquals($instance->getValue(), 'value');
    }

    /**
     * @return void
     */
    public function test_invalid_default_constructor()
    {
        Builder::forceDefaultConstructor();
        $this->expectException(\InvalidArgumentException::class);
        Builder::buildInstance(PlainValueStaticConstructor::class, ['value'], 'invalidConstructor');
        Builder::forceDefaultConstructor(false);
    }

    /**
     * @return void
     */
    public function test_skip_default_constructor()
    {
        Builder::forceDefaultConstructor(false);
        Builder::useConstructors(['create']);
        $instance = Builder::buildInstance(PlainValueStaticConstructor::class, ['value'], 'invalidConstructor');
        $this->assertInstanceOf(PlainValueStaticConstructor::class, $instance);
        $this->assertEquals($instance->getValue(), 'value');
    }
}