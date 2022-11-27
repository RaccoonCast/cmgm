<?php
// hey future cast wanting to rewrite this code because you suck less use: https://www.php.net/manual/en/function.parse-url.php
if (!str_contains($data, 'testmap')) {
  $latitude = strstr($data, "latitude="); // Remove text before latitude= for $latitude.
  $latitude = preg_replace('/[^0-9.-]/', '', strstr($latitude, "&", true));  // Remove text after "&" and remove non-numeric characters for $latitude.

  $longitude = strstr($data, "longitude="); // Remove text before longitude= for $longitude.
  $longitude = preg_replace('/[^0-9.-]/', '', strstr($longitude, "&", true));  // Remove text after "&" and remove non-numeric characters for $longitude.

  $cm_zoom = strstr($data, "zoom=") . "&blahblahblah=blah"; // Remove text before longitude= for $longitude. // Add blahblahblah text incase zoom is last paramater in the url
  $cm_zoom = preg_replace('/[^0-9.]/', '', strstr($cm_zoom, "&", true));  // Remove text after "&" and remove non-numeric characters for $longitude.

  if (strpos($data, 'MCC=310&MNC=260') !== false) {$carrier = "T-Mobile";}
  if (strpos($data, 'MCC=310&MNC=120') !== false) {$carrier = "Sprint";}
  if (strpos($data, 'MCC=310&MNC=410') !== false) {$carrier = "ATT";}
  if (strpos($data, 'MCC=311&MNC=480') !== false) {$carrier = "Verizon";}
  if (strpos($data, 'type=LTE') !== false) {$cm_netType = "LTE";}
  if (strpos($data, 'type=NR') !== false) {$cm_netType = "NR";}
} else {
  $latitude = strstr($data, "lat="); // Remove text before latitude= for $latitude.
  $latitude = preg_replace('/[^0-9.-]/', '', strstr($latitude, "&", true));  // Remove text after "&" and remove non-numeric characters for $latitude.

  $longitude = strstr($data, "lng="); // Remove text before longitude= for $longitude.
  $longitude = preg_replace('/[^0-9.-]/', '', strstr($longitude, "&", true));  // Remove text after "&" and remove non-numeric characters for $longitude.

  $cm_zoom = strstr($data, "z=") . "&blahblahblah=blah"; // Remove text before longitude= for $longitude. // Add blahblahblah text incase zoom is last paramater in the url
  $cm_zoom = preg_replace('/[^0-9.-]/', '', strstr($cm_zoom, "&", true));  // Remove text after "&" and remove non-numeric characters for $longitude.

  if (strpos($data, '310/260') !== false) {$carrier = "T-Mobile";}
  if (strpos($data, '310/120') !== false) {$carrier = "Sprint";}
  if (strpos($data, '310/410') !== false) {$carrier = "ATT";}
  if (strpos($data, '311/480') !== false) {$carrier = "Verizon";}

  if (strpos($data, 'LTE') !== false) {$cm_netType = "LTE";} // while it may appear that LTE & NR code can probably be outside of this if string they shouldn't because...
  if (strpos($data, 'NR') !== false) {$cm_netType = "NR";} // non-testmap strings contain &showLTECAonly regardless of whether it is LTE OR NR.
}

$conv_type = "CellMapper";
?>
