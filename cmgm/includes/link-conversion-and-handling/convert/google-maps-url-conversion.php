<?php
if(substr("$data", 0, 33) === 'https://www.google.com/maps/d/u/0') {
  $latitude = explode('ll=', $data, 2)[1];
  $longitude = explode('%2C', $data, 2)[1];
  $latitude = substr($latitude,0,10);
  $longitude = substr($longitude,0,10);
      $conv_type = "Google Maps";
} else {
  $first = substr($data, strpos($data, "@") + 1);
  $arr = explode("/", $first, 2);
  $second = $arr[0];
  $str_arr = preg_split ("/\,/", $second);
  $latitude = $str_arr[0];
  $longitude = $str_arr[1];
  $conv_type = "Google My Maps";
}
?>
