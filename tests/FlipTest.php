<?php

use PHPUnit\Framework\TestCase;
use Lib\Flip;
use Lib\Db;
use Lib\Config;
use Faker\Factory;

class FlipTest extends TestCase
{
    protected $dbh;

    /**
     * Setup test: preparing database
     */
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
    }

    /**
     * Setup test, before everyting run: run migration
     */
    public static function setUpBeforeClass() :void
    {
        $db = new Db();

        $db->migrateUp('disbursements', [
            'id' => 'INT UNSIGNED PRIMARY KEY',
            'amount' => 'DECIMAL(10) NOT NULL',
            'status' => 'VARCHAR(10) NOT NULL',
            'transaction_timestamp' => 'DATETIME NOT NULL',
            'bank_code' => 'VARCHAR(25) NOT NULl',
            'account_number' => 'VARCHAR(32) NOT NULL',
            'beneficiary_name' => 'VARCHAR(50) NOT NULL',
            'remark' => 'VARCHAR(255) NULL',
            'receipt' => 'VARCHAR(255) NULL',
            'time_served' => 'DATETIME NULL',
            'fee' => 'DECIMAL(5) NOT NULL',
            'created_at' => 'DATETIME NOT NULL',
            'updated_at' => 'DATETIME NULL'
        ]);
    }

    /**
     * Test disburse
     * @test
     */
    public function disburse()
    {
        $faker = Factory::create();

        $data = [
            'bank_code' => 'bni',
            'account_number' => $faker->creditCardNumber,
            'amount' => $faker->numberBetween(10000, 100000),
            'remark' => $faker->sentence
        ];

        Flip::disburse($data);

        // Check data on database test
        $disbursement = $this->dbh->query("SELECT * FROM disbursements ORDER BY created_at DESC LIMIT 1")->fetch();

        $this->assertEquals($disbursement['bank_code'], $data['bank_code']);
        $this->assertEquals($disbursement['account_number'], $data['account_number']);
        $this->assertEquals($disbursement['amount'], $data['amount']);
        $this->assertEquals($disbursement['remark'], $data['remark']);

        $this->dbh = null;
    }

    /**
     * Test get status
     * @test
     */
    public function getDisbursementStatus()
    {
        // Disburse first, to get the transaction_id to test
        $faker = Factory::create();

        $data = [
            'bank_code' => 'bni',
            'account_number' => $faker->creditCardNumber,
            'amount' => $faker->numberBetween(10000, 100000),
            'remark' => $faker->sentence
        ];

        $resp = Flip::disburse($data);

        $id = $resp['data']['id'];

        // Run getStatus
        $resp = Flip::getStatus($id);

        $disbursement = $this->dbh->query("SELECT * FROM disbursements WHERE id = $id LIMIT 1")->fetch();

        $this->assertEquals($disbursement['bank_code'], $data['bank_code']);
        $this->assertEquals($disbursement['account_number'], $data['account_number']);
        $this->assertEquals($disbursement['amount'], $data['amount']);
        $this->assertEquals($disbursement['remark'], $data['remark']);

        // assert updated data
        $this->assertEquals($disbursement['status'], $resp['data']['status']);
        $this->assertEquals($disbursement['receipt'], $resp['data']['receipt']);
        $this->assertEquals($disbursement['time_served'], $resp['data']['time_served']);
    }

    /**
     * Run after everyting: drop table
     */
    public static function tearDownAfterClass() :void
    {
        $db = new Db();
        $db->migrateDown('disbursements');

        // Delete sqlite file
        if (Config::get('database.driver') == 'sqlite') {
            unlink(__DIR__ . '/../' . Config::get('database.name'));
        }
    }
}
