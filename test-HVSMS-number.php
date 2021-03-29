<?php
/*
 * Copyright (C) 2021 Paladin Business Solutions
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("ringcentral-functions.inc");

$db = db_connect();

// $sdk = ringcentral_invoke_prod_sdk() ;
$sdk = ringcentral_invoke_prod_sdk() ;
if (is_object($sdk )) {
    echo "SDK Connected... <br/>";
} else {
    echo "<pre>" ;
    var_dump($sdk) ;
    echo "</pre>" ;
    exit();
}

/* now send a request to check the numbers */
$resp = $sdk->platform()->get('/account/~/extension/~/phone-number');
foreach ($resp->json()->records as $record){
    foreach ($record->features as $feature){
        if ($feature == "A2PSmsSender"){
            if ($record->paymentType == "TollFree")
                print_r ("This phone number " . $record->phoneNumber . " is a toll-free number and provisioned for using to send high volume SMS\n");
            else
                print_r ("This phone number " . $record->phoneNumber . " is a 10-DLC local number and provisioned for using to send high volume SMS\n");
        }
        echo "other numbers: " . $record->phoneNumber . "<br/><br/>" ;
    }
}
?>