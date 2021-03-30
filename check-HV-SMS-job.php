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

/* ==================================== */
/* us the batch ID to check job status  */
/* ==================================== */
$batch_id = "e884f3a1-8446-4780-b4b2-56edc98db1eb" ;    

try {
    $resp = $sdk->platform()->get("/restapi/v1.0/account/~/a2p-sms/batch/$batch_id");
    echo "<pre>" ;
    print_r ($resp->json());
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