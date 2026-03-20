<?php
if (($key == "latitude" OR $key == "longitude") && @${@$key} != $value) {
  if(strpos($value, ',') !== false) {
  $result = explode(",", $value);
  $tmp_latitude = substr(trim($result[0]), 0, 12);
  $tmp_longitude = substr(trim($result[1]), 0, 12);

  if ((is_numeric($tmp_latitude)) and (is_numeric($tmp_longitude))) {
    $sql_edit .= "latitude = '".mysqli_real_escape_string($conn, $tmp_latitude)."', ";
    $sql_edit .= "longitude = '".mysqli_real_escape_string($conn, $tmp_longitude)."', ";
    @$vals .= 'Coordinates changed from "' . $latitude . ", " . $longitude . '" to "' . $tmp_latitude . ", " .$tmp_longitude . '"'. PHP_EOL.'';
  }
  $do_not_read_longitude = "true";
} elseif(@$do_not_read_longitude != "true") {
  $sql_edit .= "$key = '".mysqli_real_escape_string($conn, substr($value, 0, 12))."', ";
  include "history.php";
}
}
?>
