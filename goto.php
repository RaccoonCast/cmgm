<?php
if (!empty($_GET['latitude'])) { $latitude = $_GET['latitude']; }
if (!empty($_GET['longitude'])) { $longitude = $_GET['longitude']; }
if (!empty($_GET['carrier'])) { $carrier = $_GET['carrier']; }
if (!empty($_GET['zip'])) { $zip = $_GET['zip']; }
if (!empty($_GET['state'])) { $state = $_GET['state']; }
if (!empty($_GET['city'])) { $city = $_GET['city']; }
if (!empty($_GET['address'])) { $address = $_GET['address']; }
if (!empty($_GET['goto_page'])) { $goto_page = $_GET['goto_page']; } else {
  echo 'ERROR: $goto_page variable was not set.';
  die();
}
if ($goto_page == "CellMapper") $goto_page_URL = "basic-redirect.php?goto_page=cm&";
if ($goto_page == "Google Maps") $goto_page_URL = "basic-redirect.php?goto_page=gm&";
if ($goto_page == "LA Permit Map") $goto_page_URL = "basic-redirect.php?goto_page=permit-map&";
if ($goto_page == "Database") $goto_page_URL = "HubDatabase.php?";
if ($goto_page == "Map") $goto_page_URL = "database/Map.php?";
if ($goto_page == "DB") $goto_page_URL = "database/DB.php?";
if ($goto_page == "Home") $goto_page_URL = "Home.php?";
if(empty($carrier)) $carrier = null;
if(empty($zip)) $zip = null;
if(empty($city)) $city = null;
if(empty($state)) $state = null;
if(empty($address)) $address = null;

$suffix_part_b = '&address=' . $address . '&zip=' . $zip . '&city=' . $city .'&state=' . $state;
$suffix_part_a = '&carrier=' . $carrier;

if ($goto_page == "DB") { $suffix_part_a = null; $suffix_part_b = null; }
if ($goto_page == "CellMapper") { $suffix_part_b = null; }
if ($goto_page == "Google Maps") { $suffix_part_a = null; $suffix_part_b = null; }
if ($goto_page == "Map") $suffix_part_b = null;

if (isset($goto_page_URL)) {
$the_URL = "$goto_page_URL" . "latitude=$latitude&longitude=$longitude" . "$suffix_part_a" . "$suffix_part_b";
echo '<meta http-equiv="refresh" content="0; url=' . $the_URL . '">';
} else {
  echo 'ERROR: "' . $goto_page . '" does not have a specified redirect page.';
  die();
}
