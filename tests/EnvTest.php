<?php

use PHPUnit\Framework\TestCase;
use Lib\Env;

class EnvTest extends TestCase
{
    /**
     * Test get env default value, if not exists
     * @test
     */
    public function defaultValue()
    {
        $someval = Env::get('SOMEVAL', 'defaultValue');

        $this->assertEquals($someval, 'defaultValue');
    }

    /**
     * Test get env value
     * @test
     */
    public function value()
    {
        $dbdriver = Env::get('DB_DRIVER');

        $this->assertEquals($dbdriver, 'sqlite');
    }
}
