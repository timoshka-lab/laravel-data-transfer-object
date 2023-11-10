<?php

namespace TimoshkaLab\DataTransferObject\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use TimoshkaLab\DataTransferObject\Factory;

abstract class TestCase extends Orchestra
{
    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->refreshApplication();
        Factory::resetConstructors();
        Factory::forceDefaultConstructor(false);
    }
}