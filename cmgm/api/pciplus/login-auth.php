<?php
//  cody and alps' purple iphones (CAAPI)
include "includes/functions.php"; // respond function + sqlpw + json headers

function removeLoginInfo($conn, $pciPlusIdent) {
  mysqli_query($conn, "UPDATE userID set pciplus_token = '', pciplus_timestamp = '' WHERE pciplus_token='$pciPlusIdent'"); 
}

$pciPlusIdent = mysqli_real_escape_string($conn, @$_GET['pciplusIdent']);
if (isset($pciPlusIdent)) {
    $cmgm_userid = mysqli_fetch_array(mysqli_query($conn, "SELECT userID, pciplus_timestamp from userID where pciplus_token = '$pciPlusIdent'"));
    $userid_token = @$cmgm_userid['userID'];
    $userid_timestamp = @$cmgm_userid['pciplus_timestamp'];
    $time_now = time();
  
    if (empty($userid_token) OR empty($userid_timestamp)) {
      respond(401, "IDENTIFIER_NOT_IN_DB"); // no result found
    } else {
      // remove temp info from db
      removeLoginInfo($conn, $pciPlusIdent);
      
      // session authenticated succesfully, respond with user_id
      respond(200, $userid_token);
    }
  
    // Check if timestamp reached limit
    if ($time_now - $userid_timestamp > 300) { // Maximum 5 mins timestamp difference
      // remove temp info from db
      removeLoginInfo($conn, $pciPlusIdent);
      
      respond(401, "MISSED_TIMESTAMP_NEEDS_REAUTH");
    }
    
} else {
    respond(400, "REQUEST_MISSING_IDENTIFIER"); // identifier not sent with request
}

?>