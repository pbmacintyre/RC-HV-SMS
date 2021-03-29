<?php

/*
* Copyright (C) 2021 Paladin Business Solutions
*/
error_reporting(E_ALL);
ini_set('display_errors', 1);

/* ====================================== */
/* bring in generic ringcentral functions */
/* ====================================== */
require_once("ringcentral-functions.inc");

$db = db_connect();

$sdk = ringcentral_invoke_sandbox_sdk() ;
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
$from = ringcentral_get_from_phone();

/* info for single message to array of numbers */
$to = array(
    array('phoneNumber' => '9029405827'),
//     array('phoneNumber' => '9029402562'),
    ) ;

$message = "body of the text message.";

try {    
    $resp = $sdk->platform()->post("/account/~/extension/~/sms",
        array('from' => array('phoneNumber' => $from),            
            'to'   => $to,
            'text' => $message ) );
} catch (\RingCentral\SDK\Http\ApiException $e) {
    $apiResponse = $e->apiResponse();    
    // craft a friendly message here.
    echo "<h2>There was an error sending the SMS message </h2><font color='red'>'" . $message . "'</font><br/>";
//     echo "<pre>" ;
//     var_dump($apiResponse) ;
//     echo "</pre>" ;
}

echo "Request ID: " . $resp->json()->id;

echo "<pre>" ;
var_dump($resp->json());
echo "</pre>" ;
    
    
    
    
    
    
    
?>