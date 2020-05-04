<?php
include "../functions.php";

// Upload code
define("UPLOAD_DIR", "uploads/");


// Call google to convert latitude & longitude
  $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($latitude).','.trim($longitude).'&key=' . $api_key . '';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$response = curl_exec($ch);
curl_close($ch);

// Parse the json output

 $response = json_decode($response);

 $addressComponents = $response->results[0]->address_components;
 foreach ($addressComponents as $addrComp) {
     if ($addrComp->types[0] == 'postal_code') $zip = $addrComp->long_name;
     if ($addrComp->types[0] == 'street_number') $number = $addrComp->short_name;
     if ($addrComp->types[0] == 'route') $name = $addrComp->short_name;
     if ($addrComp->types[0] == 'locality') $city = $addrComp->short_name;
     if ($addrComp->types[0] == 'administrative_area_level_1') $state = $addrComp->short_name;
     $address = "$number $name";
     }
// get text data from form

$type = $_GET['type'];
$carrier = $_GET['carrier'];
$id = $_GET['id'];
$bands = $_GET['bands'];
$firstseen = $_GET['date'];
$bio = $_GET['bio'];
if(isset($_GET['evidence_text'])) $evidence_text = $_GET['evidence_text'];
if(isset($_GET['carrier_multiple'])) $carrier_multiple = $_GET['carrier_multiple'];

// get evidence checkbox values

if(isset($_GET['permit_cellsite'])) $permit_cellsite = $_GET['permit_cellsite'];
if(isset($_GET['permit_suspected_carrier'])) $permit_suspected_carrier = $_GET['permit_suspected_carrier'];
if(isset($_GET['trails_match'])) $trails_match = $_GET['trails_match'];
if(isset($_GET['other_carriers_dont'])) $other_carriers_dont = $_GET['other_carriers_dont'];
if(isset($_GET['antennas_match_carrier'])) $antennnas_match_carrier = $_GET['antennnas_match_carrier'];
if(isset($_GET['evidence_text'])) $evidence_text = $_GET['evidence_text'];

// change missing variables to false if missing
if(!isset($permit_cellsite)) $permit_cellsite = 'false';
if(!isset($permit_suspected_carrier)) $permit_suspected_carrier = 'false';
if(!isset($trails_match)) $trails_match = 'false';
if(!isset($other_carriers_dont)) $other_carriers_dont = 'false';
if(!isset($antennnas_match_carrier)) $antennnas_match_carrier = 'false';

// calculate evidence score (numerical value)
$evidence_score = 0;

if($permit_cellsite == 'true') $evidence_score = ($evidence_score) + (1);
if($permit_suspected_carrier == 'true') $evidence_score = ($evidence_score) + (20);
if($trails_match == 'true') $evidence_score = ($evidence_score) + (5);
if($other_carriers_dont == 'true') $evidence_score = ($evidence_score) + (3);
if($antennnas_match_carrier == 'true') $evidence_score = ($evidence_score) + (1);


if (empty($firstseen)) {

} else {
  $date = str_replace('/"', '-', $firstseen);
  $firstseen = date("Y/m/d", strtotime($date));
}

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "INSERT INTO database_db (`id`, `carrier`,`carrier_multiple`,`type`,`latitude`,`longitude`,`firstseen`,`bands`,`city`,`zip`,`state`,`address`,`bio`,`permit_cellsite`,
                                 `permit_suspected_carrier`,`trails_match`,`other_carriers_dont`,`antennas_match_carrier`,`evidence_score`,`evidence_text`)
                      VALUES (
                        '".mysqli_real_escape_string($conn, $id)."',
                        '".mysqli_real_escape_string($conn, $carrier)."',
                        '".mysqli_real_escape_string($conn, $carrier_multiple)."',
                        '".mysqli_real_escape_string($conn, $type)."',
                        '".mysqli_real_escape_string($conn, $latitude)."',
                        '".mysqli_real_escape_string($conn, $longitude)."',
                        '".mysqli_real_escape_string($conn, $firstseen)."',
                        '".mysqli_real_escape_string($conn, $bands)."',
                        '".mysqli_real_escape_string($conn, $city)."',
                        '".mysqli_real_escape_string($conn, $zip)."',
                        '".mysqli_real_escape_string($conn, $state)."',
                        '".mysqli_real_escape_string($conn, $address)."',
                        '".mysqli_real_escape_string($conn, $bio)."',
                        '".mysqli_real_escape_string($conn, $permit_cellsite)."',
                        '".mysqli_real_escape_string($conn, $permit_suspected_carrier)."',
                        '".mysqli_real_escape_string($conn, $trails_match)."',
                        '".mysqli_real_escape_string($conn, $other_carriers_dont)."',
                        '".mysqli_real_escape_string($conn, $antennnas_match_carrier)."',
                        '".mysqli_real_escape_string($conn, $evidence_score)."',
                        '".mysqli_real_escape_string($conn, $evidence_text)."');  ";

if (mysqli_query($conn, $sql)) {
    echo '<meta http-equiv="refresh" content="3;URL=../" /> ';
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);


?>
