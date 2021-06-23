<?php
// Edit
$sql_edit = "UPDATE database_db SET ";
$vals = "$";

// Add all the edited fields to the $sql_edit query.
if (isset($_POST['id'])) foreach ($list_of_vars as $value) {
    if ($_POST[$value] != @${$value} && @$value != "edit_history") {
      if (strpos($value, 'street_view_url') === false) $sql_edit .= "$value = '".mysqli_real_escape_string($conn, $_POST[$value])."', ";
      if (strpos($value, 'street_view_url') !== false) $sql_edit .= "$value = '".mysqli_real_escape_string($conn, str_replace("https://", "",$_POST[$value]))."', ";
      @$vals .= $value . ", $";
    }
    ${$value} = $_POST[$value];
  }
// Remove last comma from the query.
if (isset($id)) $vals = rtrim($vals,', $');

if (strlen($sql_edit) != 23) {
  date_default_timezone_set("America/Los_Angeles");
  if (isset($_POST['new'])) $edit_history = date("Y-m-d H:i:s") . " PST: User $username ($userIP) created.";
  if (isset($edit_history)) $edit_history .= "\r\n";
  $sql_edit .= " edit_history = '$edit_history" . date("Y-m-d H:i:s") . " PST: User $username ($userIP) edited $vals.' WHERE id = $id";
  mysqli_query($conn, $sql_edit);

  include "read_data.php";
}
// if (isset($_POST['id'])) {
//   $tmp_id = $id+1;
//   redir("?id=$tmp_id",0);
// }
?>
