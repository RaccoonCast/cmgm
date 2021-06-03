<?php
//if latitude and longitude still not set try DMS to LatLong conversion
function DMStoDEC($deg,$min,$sec) {
  return $deg+((($min*60)+($sec))/3600);
}
if (substr("$data", 11, 1) === 'N') {
$data = str_replace(',', '', $data);
// 40-40-54.4 N (40) (40) (54.4) (N) AND 34-05-50.9 N (34) (05) (50.9) (N)
$deg1 = substr($data,0,2);
$min1 = substr($data,3,2);
$sec1 = substr($data,6,4);

// 73-59-36.3 W (77) (59) (36.3) (W) AND 118-15-15.7 W (118) (15) (51.7) (W)
// check for 3 digit $deg2
if (substr("$data", 16, 1) === '-') {
  $deg2 = substr($data,13,3);
  $min2 = substr($data,17,2);
  $sec2 = substr($data,20,4);
} else {
  $deg2 = substr($data,13,2);
  $min2 = substr($data,16,2);
  $sec2 = substr($data,19,4);
}
} else {
  // 40-40-54.4N (40) (40) (54.4) (N) AND 34-05-50.9N (34) (05) (50.9) (N)
  $deg1 = substr($data,0,2);
  $min1 = substr($data,3,2);
  $sec1 = substr($data,6,4);

  // 73-59-36.3W (77) (59) (36.3) (W) AND 118-15-15.7W (118) (15) (51.7) (W)
  // check for 3 digit $deg2
  if (substr("$data", 15, 1) === '-') {
    $deg2 = substr($data,12,3);
    $min2 = substr($data,16,2);
    $sec2 = substr($data,19,4);
  } else {
    $deg2 = substr($data,13,2);
    $min2 = substr($data,16,2);
    $sec2 = substr($data,19,4);
  }
}
$latitude = DMStoDec($deg1,$min1,$sec1);
$longitude = DMStoDec($deg2,$min2,$sec2);
if(strpos($data, "S") !== false) $latitude = "-".$latitude;
if(strpos($data, "W") !== false) $longitude = "-".$longitude;

$conv_type = "DMS Coordinates";
?>
