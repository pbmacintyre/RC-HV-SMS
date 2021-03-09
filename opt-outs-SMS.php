<?php
/*
 * Copyright (C) 2021 Paladin Business Solutions
 */
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once("ringcentral-functions.inc");

$db = db_connect();

$sdk = ringcentral_invoke_sdk() ;
if (is_object($sdk )) {
    echo "SDK Connected... <br/>";
} else {
    echo "<pre>" ;
    var_dump($sdk) ;
    echo "</pre>" ;
    exit();
}

/* now send a simple SMS message */
/* get the phone number from which the message will be sent */
    
$params = array( 'from' => ringcentral_get_from_phone() );

try {
//     $resp = $sdk->platform()->get("/account/~/extension/~/sms/opt-outs", $params);    
    $resp = $sdk->platform()->get("/restapi/v1.0/account/~/a2p-sms/opt-outs", $params);    
} catch (\RingCentral\SDK\Http\ApiException $e) {    
    $apiResponse = $e->apiResponse();
    // craft a friendly message here.    
    echo "<h2>There was an error communicating with the RingCentral servers </h2>";    
    echo "<pre>" ;
    var_dump($apiResponse) ;
    echo "</pre>" ;
}

echo "<pre>" ;
var_dump($resp->json());
echo "</pre>" ;

?>