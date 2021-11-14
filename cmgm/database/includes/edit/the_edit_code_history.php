<?php
if (!isset($_POST['new'])) {
  if (!empty($value)) {
    if (!empty(@${@$key})) @$vals .= "$" . $key . ' value changed from "' . preg_replace( "/\r|\n/", "", @${@$key}) . '" to "' . preg_replace( "/\r|\n/", "", $value ) . '" \r\n';
    if (empty(@${@$key})) @$vals .= "$" . $key . ' value set to "' . preg_replace( "/\r|\n/", "", $value ) . '" \r\n';
  }
  if (empty($value)) @$vals .= "$" . $key . ' value removed, $' . $key . ' formerly "' . preg_replace( "/\r|\n/", "", @${@$key}) . '" \r\n';
} else {
  @$vals .= "$" . $key . ' value set to "' . preg_replace( "/\r|\n/", "", $value ) . '" \r\n';
}
 ?>
