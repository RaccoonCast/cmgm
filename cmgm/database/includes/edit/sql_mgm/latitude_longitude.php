<?php
if ($key == "latitude" && @${@$key} != $value ) {
  if(strpos($value, ',') !== false) {
  $result = explode(",", $value);
  $tmp_latitude = trim($result[0]);
  $tmp_longitude = trim($result[1]);

  if ((is_numeric($tmp_latitude)) and (is_numeric($tmp_longitude))) {
    $sql_edit .= "latitude = '".mysqli_real_escape_string($conn, $tmp_latitude)."', ";
    $sql_edit .= "longitude = '".mysqli_real_escape_string($conn, $tmp_longitude)."', ";
    @$vals .= 'Coordinates changed from "' . $latitude . ", " . $longitude . '" to "' . $tmp_latitude . ", " .$tmp_longitude . '"'. PHP_EOL.'';
  }
} else {
  $sql_edit .= "$key = '".mysqli_real_escape_string($conn, $value)."', ";
  include "history.php";
}
}
?>
