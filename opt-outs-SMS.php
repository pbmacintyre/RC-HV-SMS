<?php
/*
 * Copyright (C) 2021 Paladin Business Solutions
 */

require_once("ringcentral-functions.inc");

$db = db_connect();

$sdk = ringcentral_invoke_prod_sdk() ;
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
    
$params = array( 'from' => ringcentral_get_prod_from_phone(3) );

try {    
    $resp = $sdk->platform()->get("/restapi/v1.0/account/~/a2p-sms/opt-outs", $params);    
    echo "<pre>" ;
    var_dump($resp->json());
    echo "</pre>" ;
} catch (\RingCentral\SDK\Http\ApiException $e) {    
    $apiResponse = $e->apiResponse();
    // craft a friendly message here.    
    echo "<h2>There was an error communicating with the RingCentral servers </h2>";    
    echo "<pre>" ;
    var_dump($apiResponse) ;
    echo "</pre>" ;
}

?>