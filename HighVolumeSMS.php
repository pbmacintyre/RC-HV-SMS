<?php

/*
* Copyright (C) 2021 Paladin Business Solutions
*/
error_reporting(E_ALL);
ini_set('display_errors', 0);

/* ====================================== */
/* bring in generic ringcentral functions */
/* ====================================== */
require_once("ringcentral-functions.inc");

$db = db_connect();

$sdk = ringcentral_invoke_sdk() ;
if (is_object($sdk )) { 
    echo "<h3>SDK Connected... </h3><br/>";
} else {
    echo "<pre>" ; 
    var_dump($sdk) ;
    echo "</pre>" ;
    exit();
}

/* ================ */
/* High Volume code */
/* ================ */
/* get the phone number from which the message will be sent */
$from = ringcentral_get_from_phone();

/*
// SQL code for getting client numbers and building array of these numbers.
$sql = "SELECT `mobile_number` FROM `ringcentral_clients` LIMIT 8000" ;
$result = $db->query($sql);

while ($row = $result->fetch_assoc()){
    // echo "#: " . $row['mobile_number'] . "<br/>";
    $to[]= array('phoneNumber' => $row['mobile_number']);
}

$requestBody = array(
    'from' => $from,
    "text" => "Hello Team",
    'messages' => $to
);

/* ======================== */
/* Send custom messaging to */   
/* separate numbers         */
/* ======================== */

// SQL code for getting client names and numbers.
$sql = "SELECT `first_name`,`mobile_number` FROM `ringcentral_clients` LIMIT 8000" ;
$result = $db->query($sql);

while ($row = $result->fetch_assoc()){
    // echo "#: " . $row['mobile_number'] . "<br/>";
    $messages[]= array(
        'to' => $row['mobile_number'],
        'text' => "Hello " . $row['first_name'] . " Here is a customized message."
    );
}

$requestBody = array(
    'from' => $from,
    'messages' => $messages
);

// check the estimated size of the outgoing request.
if (get_MBs($requestBody, 45)) {
    echo "The MB Size of the HV SMS request exceeds size limits" . "<br/>" ;
    echo "Mega Bytes: " . $megaBytes . "<br/>" ;
    exit();
}
// echo "Mega Bytes: " . $megaBytes . "<br/>" ;

try {    
    $resp = $sdk->platform()->post('/restapi/v1.0/account/~/a2p-sms/batch', $requestBody);
    $job_ID = $resp->json()->id;
} catch (\RingCentral\SDK\Http\ApiException $e) {
    $apiResponse = $e->apiResponse();    
    // craft a friendly message here.
    echo "<h2>There was an error sending the HV SMS messages </h2><br/>";
    echo "<pre>" ;
    var_dump($apiResponse) ;
    echo "</pre>" ;
}






?>