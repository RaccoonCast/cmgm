<?php
if (!isset($_POST['new'])) {
  if (!empty($value)) {
    if (!empty(@${@$key})) @$vals .= "$" . $key . ' value changed from "' . @${@$key} . '" to "' . $value . '" \r\n';
    if (empty(@${@$key})) @$vals .= "$" . $key . ' value set to "' . $value . '" \r\n';
  }
  if (empty($value)) @$vals .= "$" . $key . ' value removed, $' . $key . ' formerly "' . @${@$key} . '" \r\n';
} else {
  @$vals .= "$" . $key . ' value set to "' . $value . '" \r\n';
}
 ?>
