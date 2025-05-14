<?php
$curr_userIP = $_SERVER["REMOTE_ADDR"];

if (!defined('SITE_ROOT')) {
define ('SITE_ROOT', $_SERVER['DOCUMENT_ROOT']);
}

include SITE_ROOT . "/includes/functions/sqlpw.php";

// Check to see if browser has a USER ID cookie and if it does create a variable called "cookie_userID" with that value.
if (isset($_COOKIE['userID'])) {
  $cookie_userID = preg_replace("/[^a-zA-Z0-9]/", '', $_COOKIE['userID']);;
} elseif(isset($_POST['polyUserID'])) {
  $cookie_userID = preg_replace("/[^a-zA-Z0-9]/", '', $_POST['polyUserID']);;
}  else {
  $cookie_userID = null;
}

// Get userID data SQL for user with the browser's IP or the browser's userID cookie.
//if (isset($_GET['cellmapperissexy'])) $cookie_userID = "guest";

if (!isset($_GET['switchUser'])) {
  $sql = "SELECT * FROM userID WHERE userID='$cookie_userID'";
  $result = mysqli_query($conn,$sql);
  
  while($row = $result->fetch_assoc()) {
      foreach ($row as $key => $value) {
        if ($key != "id") {
          $$key = $value;
          if (@$debug_flag == "3") {
            echo basename(__FILE__) . ": " . "Setting $" . $key . " to have value '" . $value . "'<br>";
          }
        }
      }
    }
}

// If the above code failed, $userIP variable would NOT be set, this means no entry... New IP.php we go!
if (!isset($userIP)) {
  if (!isset($allowGuests)) {
    include "newIP.php";
  }
  if (isset($allowGuests)) {
    // echo "You are justaguest190";
    $userID = "guest";
    $theme = "black";
    $default_carrier = "ATT";
    $accent_color = "ff0000";
    $gmaps_util = "0";
    $debug_flag = "0";
    $prefLocType = "gps";
    $cm_mapType = "osm_street";
    $cm_zoom = "18";
    $cm_groupTowers = "false";
    $cm_showLabels = "true";
    $cm_showLowAcc = "true";
    $default_latitude = "41.87846175857693";
    $default_longitude = "-87.62886586726619";
    $gmaps_api_key_access = "false";
    $cmgm_edit_hide_edit_history = "false";
    $cmgm_edit_history_compact = "false";
    $cmgm_edit_pinspace_warn = "false";
    $cmgm_edit_override_panels_widths = "false";
    $cmgm_edit_panel1_width = "56";
    $map_map_pin_limit = "550";
    $map_edit_pin_limit = "100";
    $map_map_mobile_pin_limit = "300";
    $map_edit_mobile_pin_limit = "25";
  }
}

if($userID !== "guest") {
// If the IP of the current browser is not the same as the IP listed in the database update the IP in the databse with the IP of the current browser.
if (!isset($allowGuests) && $curr_userIP != $userIP) {
  mysqli_query($conn,"UPDATE userID SET userIP = '$curr_userIP' WHERE userID = '$cookie_userID'");
}

// Update last_date in user ID table.
if (substr(@$last_date, 0, 10) == date('Y-m-d') && !isset($allowGuests)) {
 mysqli_query($conn,"UPDATE userID SET userIP = '$curr_userIP' WHERE userID = '$cookie_userID'"); // just copied & pasted code to force an update in db.
}

if (isset($gmaps_api_key_access)) if ($gmaps_api_key_access == 'true') $maps_api_key = file_get_contents($siteroot . "/secret_maps_api_key.hiddenpass", true);
}

?>
