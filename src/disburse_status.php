<?php

include('autoload.php');

use Lib\Flip;

if (count($argv) == 1 || count($argv) > 2)
    die('Usage: php disburse_status.php {id}' . "\n");

$id = $argv[1];

echo "\n";
echo "Get status for ID " . $id . ' ...';
echo "\n\n";

$response = Flip::getStatus($id);

echo 'Status for ID ' . $id . ' (' . $response['data']['bank_code'] . ' - ' . $response['data']['account_number'] . '): ' . $response['data']['status'];
echo "\n";
