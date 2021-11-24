<?php
// Edit
$sql_edit = "UPDATE database_db SET ";
if (isset($_POST['edittag'])) foreach ($_POST as $key => $value) {
  include "the_edit_code_latlong.php";

  if (@${@$key} != $value && $key != "evidence_score" && $key != "edittag" && $key != "latitude" && $key != "edit_history" && @$key != "edit_lock" && @$key != "id" && @$key != "new" && @$key != "date_added" && $key != "multiplier") {
    if (strpos($key, 'sv') === false) $sql_edit .= "$key = '".mysqli_real_escape_string($conn, $value)."', ";
    if (strpos($key, 'sv') !== false) $sql_edit .= "$key = '".mysqli_real_escape_string($conn, str_replace("https://", "",$value))."', ";
    include "the_edit_code_history.php";
  }
  ${$value} = @$_POST[$value];
}

if (strlen($sql_edit) != 23) {
  date_default_timezone_set("America/Los_Angeles");
  $sql_edit .= "edit_date = '" . date("Y-m-d H") . "', ";
  $sql_edit .= "edit_userid = '" . $userID . "', ";

  // echo "Former: " . $edit_date . "<br>";
  // echo "Current: " . date("Y-m-d H") . "<br>";
  // echo "Former: " . $edit_userid . "<br>";
  // echo "Current: " . $userID . "<br>";
  if (isset($_POST['new'])) {
    $edit_history_value = "$edit_history" . "————— " . date("Y-m-d H:i") . " | $username created —————" . PHP_EOL . "$vals";
    $sql_edit .= " edit_history = '".mysqli_real_escape_string($conn, $edit_history_value)."' WHERE id = $id";
  }

  if (!isset($_POST['new']))  {
  if ($edit_date != date("Y-m-d H") OR $edit_userid != $userID) {
    $edit_history_value = "$edit_history" . "————————— " . date("Y-m-d H:i") . " | $username —————————" . PHP_EOL . "$vals";
    $sql_edit .= " edit_history = '".mysqli_real_escape_string($conn, $edit_history_value)."' WHERE id = $id";
  } else {
    $edit_history_value = "$edit_history" . "$vals";
    $sql_edit .= " edit_history = '".mysqli_real_escape_string($conn, $edit_history_value)."' WHERE id = $id";
  }
  }
  // echo $sql_edit;
  //die();
  if ((is_numeric($_POST['latitude']) && is_numeric($_POST['longitude']) OR (is_numeric(@$tmp_latitude) && is_numeric(@$tmp_longitude)))) mysqli_query($conn, $sql_edit);
  include "read_data.php";

}
?>
