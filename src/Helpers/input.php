<?php

function inputBankCode() {
    $bankCode = readline('Enter Bank Code: ');

    if ($bankCode === '' || is_null($bankCode))
        return inputBankCode();

    return $bankCode;
}

function inputAccountNumber() {
    $accountNumber = readline('Enter Account Number: ');

    if ($accountNumber === '' || is_null($accountNumber))
        return inputAccountNumber();

    return $accountNumber;
}

function inputAmount() {
    $amount = readline('Enter amount: ');

    if ($amount === '' || is_null($amount))
        return inputAmount();

    return $amount;
}

function inputRemark() {
    $remark = readline('Enter remark: ');

    if ($remark === '' || is_null($remark))
        return inputRemark();

    return $remark;
}
