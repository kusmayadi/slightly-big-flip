<?php

use PHPUnit\Framework\TestCase;
use Lib\Config;
use Lib\Db;
use Faker\Factory;

class DbTest extends TestCase
{
    protected $dbh;
    protected $testTable;

    public function setUp() :void
    {
        parent::setUp();

        if (Config::get('database.driver') == 'sqlite') {
            $this->dbh = new \PDO('sqlite:' . __DIR__ . '/../' . Config::get('database.name'));
        } else {
            $dbDriver = Config::get('database.driver');
            $dbHost = Config::get('database.host');
            $dbName = Config::get('database.name');
            $dbUser = Config::get('database.user');
            $dbPassword = Config::get('database.password');

            $this->dbh = new \PDO($dbDriver . ':host=' . $dbHost . ';dbname=' . $dbName, $dbUser, $dbPassword);
        }

        $this->testTable = 'users';
    }

    /**
     * Test Db is class
     */
    public function isObject()
    {
        $db = new Db();

        $this->assertIsObject($db);
    }

    /**
     * Test migrate up
     * @test
     */
    public function migrateUp()
    {
        $db = new Db();

        $db->migrateUp($this->testTable, [
            'id' => 'INT UNSIGNED AUTO_INCREMENT PRIMARY_KEY',
            'name' => 'VARCHAR(125) NULL',
            'email' => 'VARCHAR(255) NOT NULL'
        ]);

        $checkTable = $this->dbh->query('SELECT 1 FROM ' . $this->testTable);

        $this->assertisObject($checkTable);
    }

    /**
     * Test insert into database
     * @test
     */
    public function insert()
    {
        $db = new Db();
        $faker = Factory::create();

        $name = $faker->name;
        $email = $faker->email;

        $db->insert($this->testTable, [
            'id' => 1,
            'name' => $name,
            'email' => $email
        ]);

        $user = $this->dbh->query("SELECT * FROM $this->testTable LIMIT 1")->fetch();

        $this->assertEquals($user['name'], $name);
        $this->assertEquals($user['email'], $email);
    }

    /**
     * Test update data
     * @test
     */
    public function update()
    {
        $db = new Db();
        $faker = Factory::create();

        $name = $faker->name;
        $email = $faker->email;

        $db->update($this->testTable, 1, [
            'name' => $name,
            'email' => $email
        ]);

        $user = $this->dbh->query("SELECT * FROM $this->testTable WHERE id = 1")->fetch();

        $this->assertEquals($user['name'], $name);
        $this->assertEquals($user['email'], $email);
    }

    /**
     * Test migrate down
     * @test
     */
    public function migrateDown()
    {
        $db = new Db();

        $db->migrateDown($this->testTable);

        $checkTable = $this->dbh->query('SELECT 1 FROM ' . $this->testTable);

        $this->assertFalse($checkTable);
    }

    /**
     * Test done
     */
    public static function tearDownAfterClass() :void
    {
        parent::tearDownAfterClass();

        // Delete sqlite file
        if (Config::get('database.driver') == 'sqlite') {
            unlink(__DIR__ . '/../' . Config::get('database.name'));
        }
    }
}
