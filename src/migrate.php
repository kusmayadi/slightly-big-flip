<?php
include('autoload.php');

use Lib\Db;

if (count($argv) === 1) {
    die('Usage: php migrate.php [up|down]' . "\n");
}

$db = new Db();

switch ($argv[1]) {
    case 'up':
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
    break;

    case 'down':
        $db->migrateDown('disbursements');
    break;
}

echo "Done.\n";
