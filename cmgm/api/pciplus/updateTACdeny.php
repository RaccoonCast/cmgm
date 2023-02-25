<?php
//  cody and alps' purple iphones (CAAPI)
if (isset($_POST['userID'])) $userID = $_POST['userID']; // pci+ userID / PCI+ TAC updating
if (isset($_POST['username'])) $username = $_POST['username'] . " via PCI+"; // cm username \ PCI+ TAC updating
if (isset($_POST['id'])) $id = $_POST['id'];
date_default_timezone_set('Etc/UTC');
include '../../includes/functions/sqlpw.php'; // doesn't call native

$username = mysqli_fetch_array(mysqli_query($conn, "SELECT username FROM userID WHERE userID='$userID'"))['username'];
if ($username != "PCI+") error("Invalid user ID.",$_POST['userID']);

function error_handler($errno, $errstr, $errfile, $errline) {
    // your custom error handling logic here
    error($errstr." on line ".$errline,$_POST['id']);
}
function error($error,$id) {
  $a = array("error","search_items");
  $b = array($error,$id);
  $c = array_combine($a, $b);
  echo json_encode($c);

  http_response_code(500);
  die();
}
function report($msg,$tac) {
  $a = array("changed","tac");
  $b = array($msg,$tac);
  $c = array_combine($a, $b);
  echo json_encode($c);

  http_response_code(200);
  die();
}

$sql_edit = "UPDATE db SET tac_check_date = '" . date('Y-m-d\ H:i:s') . " UTC". "' WHERE id = $id";

if ($conn->query($sql_edit) === TRUE) {
  $val = $conn -> affected_rows;
  if ($val == "1") {
    report("true",$_POST['id']);
  } else {
    report("false",$_POST['id']);
  }
} else {
  error($conn->error,$_POST['id']);
}
 ?>
