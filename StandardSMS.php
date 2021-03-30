<?php

/*
* Copyright (C) 2021 Paladin Business Solutions
*/

/* ====================================== */
/* bring in generic ringcentral functions */
/* ====================================== */
require_once("ringcentral-functions.inc");

$db = db_connect();

$sdk = ringcentral_invoke_sandbox_sdk() ;
if (is_object($sdk)) { 
    echo "SDK Connected... <br/>";
} else {
    echo "<pre>" ; 
    var_dump($sdk) ;
    echo "</pre>" ;
    exit();
}

/* now send a simple SMS message */
/* get the phone number from which the message will be sent */
$from = ringcentral_get_sandbox_from_phone(4);

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
    echo "<h2>The text message was sent successfully </h2>";
} catch (\RingCentral\SDK\Http\ApiException $e) {
    $apiResponse = $e->apiResponse();    
    // craft a friendly message here.
    echo "<h2>There was an error sending the SMS message </h2><font color='red'>'" . $message . "'</font><br/>";

}   
    
?>