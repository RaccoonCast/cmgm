<?php
//  cody and alps' purple iphones (CAAPI)
include "includes/functions.php"; // error msg / report function
if (isset($_POST['id'])) $id = $_POST['id'];
date_default_timezone_set('Etc/UTC');

include "includes/useridvalidation.php"; // Verify PCI+ is making these requests.

$sql_edit = "UPDATE db SET tac_check_date = '" . date('Y-m-d\ H:i:s') . " UTC". "' WHERE id = $id";

if ($conn->query($sql_edit) === TRUE) {
  $val = $conn -> affected_rows;
  if ($val == "1") {
    report("true",$_POST['id'],date('Y-m-d\ H:i:s'). " UTC");
  } else {
    report("false",$_POST['id'],date('Y-m-d\ H:i:s'). " UTC");
  }
} else {
  error($conn->error,$_POST['id']);
}
 ?>
