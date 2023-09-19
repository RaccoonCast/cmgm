<?php
//  cody and alps' purple iphones (CAAPI)
include "includes/functions.php"; // error msg / report function
if (isset($_POST['id'])) $id = $_POST['id'];
date_default_timezone_set('Etc/UTC');

include "includes/useridvalidation.php"; // Verify PCI+ is making these requests.

$sql_edit = "UPDATE db SET ";
foreach ($_POST as $key => $value) {
   if (@${@$key} != $value && $key != "edittag" && @$key != "id" && $key != "username") {
     $sql_edit .= "$key = '".mysqli_real_escape_string($conn, $value)."', ";
     $region_lte = @mysqli_fetch_array(mysqli_query($conn, "SELECT region_lte FROM db WHERE id='$id'"))['region_lte'];
     include "../../database/includes/edit/sql_mgm/history.php";
   }
   ${$value} = @$_POST[$value];
}
  $edit_history = @mysqli_fetch_array(mysqli_query($conn, "SELECT edit_history FROM db WHERE id='$id'"))['edit_history'];
  date_default_timezone_set('America/Los_Angeles');
  $edit_history_value = "$edit_history" . "————————— " . date("Y-m-d H:i") . " | $username —————————" . PHP_EOL . "$vals";
  $sql_edit .= "edit_date = '" . date("Y-m-d H") . "', ";
  date_default_timezone_set('Etc/UTC');
  $sql_edit .= "tac_check_date = '" . date('Y-m-d\ H:i:s') . " UTC" . "', ";
  $sql_edit .= "edit_userid = '" . $userID . "', ";
  $sql_edit .= "edit_username = '" . $username . "', ";
  $sql_edit .= "edit_history = '".mysqli_real_escape_string($conn, $edit_history_value)."' WHERE id = $id";

  if ($conn->query($sql_edit) === TRUE) {
    $val = $conn -> affected_rows;
    $value = isset($_POST['region_lte']) ? $_POST['region_lte'] : $_POST['region_nr'];
    if ($val == "1") {
      report("true",$id,$value);
    } else {
      report("false",$id,$value);
    }
  } else {
    error($conn->error,$_POST['id'],);
  }
 ?>
