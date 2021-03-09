<?php

/*
* Copyright (C) 2021 Paladin Business Solutions
*/

/* =================== */
/* Connect to Database */
/* =================== */
function db_connect() {    
    $db_conn = new mysqli('localhost', 'paladip9_adm1n', '1q2w3e4r5t!!Q', 'paladip9_ringdev');
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        return "error connecting..." ;
    } else {
        return $db_conn ;
    }
}
/* ============================================= */
/* Get RingCentral credentials from the Database */
/* ============================================= */
function sdk_credentials(){

    global $db ;
    
    $sql = "SELECT * FROM `ringcentral_control` WHERE `ringcentral_control_id` = 1" ;
    $result = $db->query($sql);
    $row = $result->fetch_assoc() ;
    
    $rc_handshake = array(
        "client_id"        => $row['client_id'],
        "client_secret"    => $row['client_secret'],
        "user_name"        => $row['ringcentral_user_name'],
        "extension"        => $row['ringcentral_extension'] ,
        "password"         => $row['ringcentral_password'] ,
        "sandbox"          => $row['ringcentral_sandbox']
    );
    return $rc_handshake ;    
}
/* ========================================================== */
/* invoke RingCental SDK connection with provided credentials */
/* ========================================================== */
function ringcentral_invoke_sdk() {
    // Include Libraries
    require('vendor/autoload.php');
    // get an array of information to connect to the SDK
    $rc_handshake = sdk_credentials();
    
    // Use sandbox Client
    $sdk = new RingCentral\SDK\SDK($rc_handshake['client_id'],
        $rc_handshake['client_secret'], RingCentral\SDK\SDK::SERVER_SANDBOX);

    // attempt to Login via API
    try {
        $sdk->platform()->login($rc_handshake['user_name'],
            $rc_handshake['extension'], $rc_handshake['password']);
        $result = $sdk ;
    } catch (\RingCentral\SDK\Http\ApiException $e) {
        // $result = false ;
        $result = "<h2>Error connecting to the SDK, response information: </h2>" . $e ;
    }
    return $result ;    
}
/* ======================================== */
/* get from phone number from control table */
/* it is stored in the user_name field      */
/* ======================================== */
function ringcentral_get_from_phone() {
    global $db;
    
    $sql = "SELECT `ringcentral_user_name` 
        FROM `ringcentral_control` 
        WHERE `ringcentral_control_id` = 1" ;
    $result = $db->query($sql);
    $row = $result->fetch_assoc() ;    
        
    return $row['ringcentral_user_name'] ;
}
/* ===================================== */
/* Count all the text (bytes) in the     */ 
/* outgoing array to ensure it is not    */
/* over 50 MB - this is an estimate ONLY */
/* ===================================== */
function get_MBs($requestBody, $testSize) {
    $bytes = 0 ;
    foreach ($requestBody as $value ){
        if (is_array($value)) {
            foreach ($value as $value_2 ){
                if (is_array($value_2)) {
                    foreach ($value_2 as $value_3) {                        
                        $bytes += strlen($value_3) ;
                    }
                }
            }    
        } else {            
            $bytes += strlen($value) ;
        }
    }
    $mbs = $bytes / 1000000 ;
    return $mbs > $testSize ? true : false ;
}
/* ============================= */
/* generate a large text sample  */
/* ============================= */
function getLongMessage(){
    return "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sodales quis turpis quis aliquet. 
        Donec tristique, tortor vitae malesuada consectetur, tellus mauris imperdiet nulla, consectetur 
        condimentum lectus dui et arcu. Nulla cursus lorem ut risus pulvinar, in dictum nunc vestibulum. 
        Donec libero justo, ullamcorper semper nunc eget, elementum ultricies nisi. Nunc in mauris eget 
        tellus malesuada viverra in eu ipsum. Aliquam erat volutpat. Aliquam porttitor nunc a 
        sollicitudin convallis. Ut nunc nulla, bibendum non varius et, auctor in lorem. Nunc dignissim 
        vel ex ullamcorper viverra. Quisque in semper dolor, non tristique dolor. Suspendisse potenti. 
        Mauris sagittis rhoncus nisi ut accumsan. Sed magna elit, condimentum id enim laoreet, aliquet
        sagittis lectus. Nullam viverra mi eu sem sodales, a porta lorem pretium. Etiam maximus odio a 
        eleifend rutrum. Sed et dapibus justo, at mollis metus. Sed ac facilisis ex. Quisque hendrerit, 
        eros non gravida elementum, arcu ante fermentum magna, non accumsan massa arcu id metus. Cras 
        vulputate dapibus dui consectetur interdum. Nam ac mauris euismod, aliquam mauris non, 
        interdum ligula. Maecenas tincidunt imperdiet lectus ut blandit. Morbi placerat lectus ac 
        tempus lobortis." ;    
}

?>