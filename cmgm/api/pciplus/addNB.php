<?php
//  cody and alps' purple iphones (CAAPI)
include "includes/functions.php"; // error msg / report function

date_default_timezone_set('America/Los_Angeles');

// User ID Validation
if (isset($_POST['userID'])) $userID = $_POST['userID']; // pci+ userID / PCI+ TAC updating
if (isset($_POST['username'])) $username = $_POST['username'] . " via PCI+"; // cm username \ PCI+ TAC updating
if (!isset($userID)) error("User ID not set.",$_POST['userID']);
$pciplus_usr = file_get_contents($siteroot . "/secret_pciplus_login_key.hiddenpass", true);
$tmp_username = mysqli_fetch_array(mysqli_query($conn, "SELECT username FROM userID WHERE userID='$userID'"))['username'];
if ($tmp_username != $pciplus_usr) error("Invalid user ID.",$_POST['userID']);

// Get all the neccesary fields for this API.
$id = $_POST['id'];
$splitId = $_POST['splitId'];
$pos = $_POST['pos'];

// Get current username of previous editor to check if different, along with retrieving a copy of edit history.
$query = "SELECT latitude, longitude, edit_date, edit_userid, edit_history, cm_pin_distance, other_user_map_primary, cm_pin_inverted FROM db WHERE id = '$id'";
$result = mysqli_query($conn, $query);

if ($row = mysqli_fetch_assoc($result)) {
    extract($row);
}


// Add new split to db & record change for edit history.
$sql_edit = 'UPDATE db SET '.$pos.' = "'.$splitId.'"'.', ';
$vals = "$" . $pos . " value set to " . "\"$splitId\"" . "\r\n";

// Record date, user id, username of current editor.
$sql_edit .= "edit_date = '" . date("Y-m-d H") . "', ";
$sql_edit .= "edit_userid = '" . $userID . "', ";
$sql_edit .= "edit_username = '" . $username . "'," . PHP_EOL;

// Figure out date & time of last edit to determine whether PCI+ was the last editor, if so, don't add user bar.
if ($edit_date != date("Y-m-d H") OR $edit_userid != $userID) {
  $edit_history_value = "$edit_history" . "————————— " . date("Y-m-d H:i") . " | $username —————————" . PHP_EOL . "$vals";
  $sql_edit .= " edit_history = '".mysqli_real_escape_string($conn, $edit_history_value)."' WHERE id = $id";
} else {
  $edit_history_value = "$edit_history" . "$vals";
  $sql_edit .= " edit_history = '".mysqli_real_escape_string($conn, $edit_history_value)."' WHERE id = $id";
}


if ($conn->query($sql_edit) === TRUE) {
  $val = $conn -> affected_rows;
  if ($val == "1") {
    $acceptable_values = array("LTE_1", "LTE_2", "LTE_3", "LTE_4", "NR_1", "NR_2", "NR_3");
    if (in_array($pos, $acceptable_values)) {
    $ignore_checks = "true";
    $carrier = "Dish"; // not really, its a jk, no one pins dish.
    include "../../database/includes/edit/latLongMod/lte.php";
    include "../../database/includes/edit/latLongMod/nr.php";
    $data = ${$_POST['pos'] . "_coordinates_set"};
    include "../../includes/link-conversion-and-handling/convert/lat,long.php";
    } else {
      $latitude = null;
      $longitude = null;
    }
    $json_response = array('ok' => true, 'splitPinCoordinates' => array( 'latitude' => $latitude, 'longitude' => $longitude));
    echo json_encode($json_response);

    http_response_code(200);
    die();
  } else {
    report("false",$id,"cmgm is having issues");
  }
} else {
  error($conn->error,$sql_edit,);
}
?>
