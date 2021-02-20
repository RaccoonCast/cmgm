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
if ($goto_page == "Database") $goto_page_URL = "HubDatabase.php?";
if ($goto_page == "Home") $goto_page_URL = "Home.php?";
if (isset($goto_page_URL)) {
echo '<meta http-equiv="refresh" content="0; url=' . $goto_page_URL . '
latitude=' . $latitude .
'&longitude=' . $longitude .
'&carrier=' . $carrier .
'&address=' . $address .
'&zip=' . $zip .
'&city=' . $city .
'&state=' . $state .
'">';
} else {
  echo 'ERROR: "' . $goto_page . '" does not have a specified redirect page.';
  die();
}
