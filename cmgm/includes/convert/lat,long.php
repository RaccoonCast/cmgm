<?php
$result = explode(",", $data);  // Split the string by commas
$latitude = trim($result[0]);         // Clean whitespace
$longitude = trim($result[1]);         // Clean whitespace

if ((is_numeric($latitude)) and (is_numeric($longitude))) {
$input_data = str_replace(' ', '', $data);
$conv_type = "Latitude & Longitude";
} else {
  unset($latitude);
  unset($longitude);
}
?>
