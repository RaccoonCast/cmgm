<?php
// lazy fix for a bug i cba explaining
$data = str_replace('NAD 83', '', $data);

function DMStoDEC($deg,$min,$sec) {
  return $deg+((($min*60)+($sec))/3600);
}
preg_match_all('!\d+\.*\d*!', $data, $out);
$latitude = DMStoDec(@$out[0][0],@$out[0][1],@$out[0][2]);
$longitude = DMStoDec(@$out[0][3],@$out[0][4],@$out[0][5]);
if ($latitude != "0" && $longitude != "0") $conv_type = "DMS Coordinates";
if(stripos($data, "S") !== false) $latitude = "-".$latitude;
if(stripos($data, "W") !== false) $longitude = "-".$longitude;

?>
