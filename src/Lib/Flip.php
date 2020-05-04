<?php

namespace Lib;
use Lib\Http;
use Lib\Db;

class Flip
{
    /**
     * Perform disbursal
     */
    public static function disburse($data)
    {
        // Hit Flip API
        $http = new Http();
        $response = $http->post('/disburse', $data);

        if ($response['info']['http_code'] == 200) { // Success
            // Insert into DB
            $db = new Db();

            $db->insert('disbursements', [
                'id' => $response['data']['id'],
                'amount' => $response['data']['amount'],
                'status' => $response['data']['status'],
                'transaction_timestamp' => $response['data']['timestamp'],
                'bank_code' => $response['data']['bank_code'],
                'account_number' => $response['data']['account_number'],
                'beneficiary_name' => $response['data']['beneficiary_name'],
                'remark' => $response['data']['remark'],
                'receipt' => $response['data']['receipt'],
                'time_served' => $response['data']['time_served'],
                'fee' => $response['data']['fee'],
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $message = 'OK';
            $data = $response['data'];
        } else {
            $message = $response['data']['message'];
            $data = [];
        }

        return [
            'status' => $response['info']['http_code'],
            'message' => $message,
            'data' => $data
        ];
    }

    /**
     * Get status of disbursement
     */
    public static function getStatus($id)
    {
        // Hit Flip API
        $http = new Http;
        $response = $http->get('/disburse/' . $id);

        if ($response['info']['http_code'] == 200) {
            // Update database
            $db = new Db;
            $db->update('disbursements', $id, [
                'status' => $response['data']['status'],
                'receipt' => $response['data']['receipt'],
                'time_served' => $response['data']['time_served']
            ]);

            $message = 'OK';
            $data = $response['data'];
        } else {
            $message = $response['data']['message'];
            $data = [];
        }

        return [
            'status' => $response['info']['http_code'],
            'message' => $message,
            'data' => $data
        ];
    }
}
