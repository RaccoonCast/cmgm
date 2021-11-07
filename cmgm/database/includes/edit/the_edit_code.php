<?php
// Edit
$sql_edit = "UPDATE database_db SET ";
$vals = "$";
if (isset($_POST['edittag'])) foreach ($_POST as $key => $value) {
  if (@${@$key} != $value && $key != "evidence_score" && $key != "edittag" && $key != "edit_history" && @$key != "edit_lock" && @$key != "id" && @$key != "new" && @$key != "date_added") {
   if (strpos($key, 'street_view') === false) $sql_edit .= "$key = '".mysqli_real_escape_string($conn, $value)."', ";
   if (strpos($key, 'street_view') !== false) $sql_edit .= "$key = '".mysqli_real_escape_string($conn, str_replace("https://", "",$value))."', ";
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

  include "read_data.php";
}
// if (isset($_POST['id'])) {
//   $tmp_id = $id+1;
//   redir("?id=$tmp_id",0);
// }
?>
