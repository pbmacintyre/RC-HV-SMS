<?php

/*
* Copyright (C) 2021 Paladin Business Solutions
*/

/* ====================================== */
/* bring in generic ringcentral functions */
/* ====================================== */
require_once("ringcentral-functions.inc");

$db = db_connect();

$sdk = ringcentral_invoke_prod_sdk() ;
if (is_object($sdk )) { 
    echo "<h3>SDK Connected... </h3>";
} else {
    echo "Cannot connect to the SDK at this time." ;
    exit();
}

/* ================ */
/* High Volume code */
/* ================ */
/* get the phone number from which the message will be sent */
$from = ringcentral_get_prod_from_phone(3);


// SQL code for getting client numbers and building array of these numbers.
$sql = "SELECT `mobile_number` FROM `ringcentral_clients` WHERE client_id = 5 LIMIT 8000" ;
$result = $db->query($sql);

while ($row = $result->fetch_assoc()){
    //echo "#: " . $row['mobile_number'] . "<br/>";
    $to[]= array('to' => array($row['mobile_number']));
}

$requestBody = array(
    'from' => $from,
    "text" => "Testing with the RC HV SMS App",
    'messages' => $to
);

// check the estimated size of the outgoing request.
if (get_MBs($requestBody, 45)) {
    echo "The MB Size of the HV SMS request exceeds size limits" . "<br/>" ;
    echo "Mega Bytes: " . $megaBytes . "<br/>" ;
    exit();
}

try {    
    $resp = $sdk->platform()->post("/restapi/v1.0/account/~/a2p-sms/batch", $requestBody);
    echo "<h2>Message list is sent... here is the server response: </h2>";
    echo "<pre>" ;
    var_dump($resp->json()) ;
    echo "</pre>" ;
} catch (\RingCentral\SDK\Http\ApiException $e) {
    $apiResponse = $e->apiResponse();    
    // craft a friendly message here.
    echo "<h2>There was an error sending the HV SMS messages </h2>";
    echo "<pre>" ;
    var_dump($apiResponse) ;
    echo "</pre>" ;
}

?>