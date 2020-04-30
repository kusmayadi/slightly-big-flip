<?php

use PHPUnit\Framework\TestCase;
use Lib\Config;

class ConfigTest extends TestCase
{
    /**
     * Test config file not exists
     * @test
     */
    public function inexistsConfigFile()
    {
        $someconfig = Config::get('someconfig');

        $this->assertFalse($someconfig);
    }

    /**
     * Test config return array when getting parent config key
     * @test
     */
    public function isArrayConfigParentKey()
    {
        $db = Config::get('database');

        $this->assertIsArray($db);
    }

    /**
     * Test config return string value when getting full key
     * @test
     */
    public function isStringConfigKey()
    {
        $dbhost = Config::get('database.host');

        $this->assertIsString($dbhost);
    }

    /**
     * Test config value
     * @test
     */
    public function value()
    {
        $dbhost = Config::get('database.host');

        $this->assertEquals('localhost', $dbhost);
    }
}
