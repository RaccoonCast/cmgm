<?php
//  cody and alps' purple iphones (CAAPI)
if (isset($_POST['userID'])) $userID = $_POST['userID']; // pci+ userID / PCI+ TAC updating
if (isset($_POST['username'])) $username = $_POST['username'] . " via PCI+"; // cm username \ PCI+ TAC updating
if (isset($_POST['id'])) $id = $_POST['id'];
date_default_timezone_set('Etc/UTC');
include '../../includes/functions/sqlpw.php'; // doesn't call native

$username = mysqli_fetch_array(mysqli_query($conn, "SELECT username FROM userID WHERE userID='$userID'"))['username'];
if ($username != "PCI+") error("Invalid user ID.",$_POST['userID']);

function error($error,$id) {
  $a = array("error","value");
  $b = array($error,$id);
  $c = array_combine($a, $b);
  echo json_encode($c);

  http_response_code(500);
  die();
}
function error_handler($errno, $errstr, $errfile, $errline) {
    // your custom error handling logic here
    error($errstr." on line ".$errline,$_POST['id']);
}
function report($msg,$tac) {
  $a = array("changed","id");
  $b = array($msg,$tac);
  $c = array_combine($a, $b);
  echo json_encode($c);

  http_response_code(200);
  die();
}

$sql_edit = "UPDATE db SET ";
foreach ($_POST as $key => $value) {
   if (@${@$key} != $value && $key != "edittag" && @$key != "id" && $key != "username") {
     $sql_edit .= "$key = '".mysqli_real_escape_string($conn, $value)."', ";
     include "../../database/includes/edit/sql_mgm/history.php";
   }
   ${$value} = @$_POST[$value];
}
  $edit_history = @mysqli_fetch_array(mysqli_query($conn, "SELECT edit_history FROM db WHERE id='$id'"))['edit_history'];
  $edit_history_value = "$edit_history" . "————————— " . date("Y-m-d H:i") . " | $username —————————" . PHP_EOL . "$vals";
  $sql_edit .= "edit_date = '" . date("Y-m-d H") . "', ";
  $sql_edit .= "tac_check_date = '" . date('Y-m-d\ H:i:s') . " UTC" . "', ";
  $sql_edit .= "edit_userid = '" . $userID . "', ";
  $sql_edit .= "edit_username = '" . $username . "', ";
  $sql_edit .= "edit_history = '".mysqli_real_escape_string($conn, $edit_history_value)."' WHERE id = $id";

  if ($conn->query($sql_edit) === TRUE) {
    $val = $conn -> affected_rows;
    if ($val == "1") {
      report("true",$value);
    } else {
      report("false",$value);
    }
  } else {
    error($conn->error,$_POST['id']);
  }
 ?>
