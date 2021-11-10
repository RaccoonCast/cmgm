<?php
// Edit
$sql_edit = "UPDATE database_db SET ";
$vals = "$";
if (isset($_POST['edittag'])) foreach ($_POST as $key => $value) {
  if ($key == "latitude") {
    if(strpos($value, ',') !== false) {
    $result = explode(",", $value);
    $tmp_latitude = trim($result[0]);
    $tmp_longitude = trim($result[1]);

    if ((is_numeric($tmp_latitude)) and (is_numeric($tmp_longitude))) {
      $sql_edit .= "latitude = '".mysqli_real_escape_string($conn, $tmp_latitude)."', ";
      $sql_edit .= "longitude = '".mysqli_real_escape_string($conn, $tmp_longitude)."', ";
    }
  } else {
    $sql_edit .= "$key = '".mysqli_real_escape_string($conn, $value)."', ";
  }
  } elseif (@${@$key} != $value && $key != "evidence_score" && $key != "edittag" && $key != "edit_history" && @$key != "edit_lock" && @$key != "id" && @$key != "new" && @$key != "date_added") {
   if (strpos($key, 'sv') === false) $sql_edit .= "$key = '".mysqli_real_escape_string($conn, $value)."', ";
   if (strpos($key, 'sv') !== false) $sql_edit .= "$key = '".mysqli_real_escape_string($conn, str_replace("https://", "",$value))."', ";
   @$vals .= $key . ", $";
  }
  ${$value} = @$_POST[$value];
}

// Remove last comma from the query.
if (isset($id)) $vals = rtrim($vals,', $');

if (strlen($sql_edit) != 23) {
  date_default_timezone_set("America/Los_Angeles");
  if (isset($_POST['new'])) $edit_history = date("Y-m-d H:i:s") . " PST: $username ($userIP) created.";
  if (isset($edit_history)) $edit_history .= "\r\n";
  $sql_edit .= " edit_history = '$edit_history" . date("Y-m-d H:i:s") . " PST: $username ($userIP) edited $vals.' WHERE id = $id";
  if (is_numeric($_POST['latitude']) && is_numeric($_POST['longitude'])) mysqli_query($conn, $sql_edit);
  if (is_numeric(@$tmp_latitude) && is_numeric(@$tmp_longitude)) mysqli_query($conn, $sql_edit);

  include "read_data.php";
}
// if (isset($_POST['id'])) {
//   $tmp_id = $id+1;
//   redir("?id=$tmp_id",0);
// }
?>
