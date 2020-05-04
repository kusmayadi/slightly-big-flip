<?php

include('autoload.php');
require_once('Helpers/input.php');

use Lib\Flip;

$disbursements = [];

// Check mode
switch (count($argv)){
    case 1:
        // no parameters, use input.
        $bankCode = inputBankCode();
        $accountNumber = inputAccountNumber();
        $amount = inputAmount();
        $remark = inputRemark();

        $disbursements[] = [
            'bank_code' => $bankCode,
            'account_number' => $accountNumber,
            'amount' => $amount,
            'remark' => $remark
        ];
    break;

    case 2:
        // csv
        $csv = array_map('str_getcsv', file($argv[1]));

        $keys = ['bank_code', 'account_number', 'amount', 'remark'];

        foreach ($csv as $row) {
            $disbursements[] = array_combine($keys, $row);
        }
    break;

    default:
        die('Usage: php disburse.php' . "\n");
    break;
}

// Start submitting disbursements
echo "\n";
echo "Start submitting disbursements ...";
echo "\n\n";

foreach ($disbursements as $disbursement) {
    $response = Flip::disburse($disbursement);

    if ($response['status'] == 200) {
        // OK
        echo 'Disbursement for ' . $disbursement['bank_code'] . ' (' . $disbursement['account_number'] . ') has been submitted. Status: ' . $response['data']['status'] . '. ID: ' . $response['data']['id'] . "\n";
    }
}
