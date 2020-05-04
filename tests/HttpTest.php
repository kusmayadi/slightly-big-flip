<?php

use PHPUnit\Framework\TestCase;
use Lib\Config;
use Lib\Http;
use Faker\Factory;

class HttpTest extends TestCase
{
    private $data;
    private $transactionId;

    public function setUp() :void
    {
        parent::setUp();

        $faker = Factory::create();

        $this->data = [
            'bank_code' => 'bni',
            'account_number' => $faker->creditCardNumber,
            'amount' => $faker->numberBetween(10000, 100000),
            'remark' => $faker->sentence
        ];
    }

    /**
     * Test Http POST
     * @test
     */
    public function post()
    {
        $endpoint = '/disburse';

        $http = new Http();
        $response = $http->post($endpoint, $this->data);

        $this->transactionId = $response['data']['id'];

        $this->assertEquals(200, $response['info']['http_code']);
        $this->assertEquals('application/json', $response['info']['content_type']);
        $this->assertEquals($this->data['bank_code'], $response['data']['bank_code']);
        $this->assertEquals($this->data['account_number'], $response['data']['account_number']);
        $this->assertEquals($this->data['amount'], $response['data']['amount']);
        $this->assertEquals($this->data['remark'], $response['data']['remark']);
    }

    /**
     * Terst Http GET
     * @test
     */
    public function get()
    {
        $endpoint = '/disburse';

        $http = new Http();
        $response = $http->get($endpoint . '/5535152564');

        $this->assertEquals(200, $response['info']['http_code']);
        $this->assertEquals('application/json', $response['info']['content_type']);
        $this->assertEquals('bni', $response['data']['bank_code']);
        $this->assertEquals('1234567890', $response['data']['account_number']);
        $this->assertEquals('10000', $response['data']['amount']);
        $this->assertEquals('sample remark', $response['data']['remark']);
    }
}
