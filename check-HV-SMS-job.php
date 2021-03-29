<?php
/*
 * Copyright (C) 2021 Paladin Business Solutions
 */
error_reporting(E_ALL);
ini_set('display_errors', 0);

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
$batch_id = "46b136f3-b404-4d94-ac42-df8b17e21ee0" ;    


try {
//     $platform = $sdk->platform();
//     $platform->login( "username", "extension_number", "password" );
    
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