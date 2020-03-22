<?php
include "functions.php";
// Call google to convert latitude & longitude
$url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($latitude).','.trim($longitude).'&key=AIzaSyAhNIGTtBPudtLxXejJfRkcT4aVwATAYs8';

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
    if ($addrComp->types[0] == 'postal_code') {
        $zip = $addrComp->long_name;
    }
    if ($addrComp->types[0] == 'street_number') {
        $number = $addrComp->short_name;
    }
    if ($addrComp->types[0] == 'route') {
        $name = $addrComp->short_name;
    }
    if ($addrComp->types[0] == 'locality') {
        $city = $addrComp->short_name;
    }
    if ($addrComp->types[0] == 'administrative_area_level_1') {
        $state = $addrComp->short_name;
    }
}

$address = "$number $name";

// Get data from the form

$type = $_GET['type'];
$carrier = $_GET['carrier'];
$id = $_GET['id'];
$bands = $_GET['bands'];
$firstseen = $_GET['date-1'];
$firstseen2 = $_GET['date-2'];
$bio = $_GET['bio'];

if (empty($firstseen2)) {
  $date = str_replace('/"', '-', $firstseen);
} else {
  $date = str_replace('/"', '-', $firstseen2);
}
$firstseen = date("Y/m/d", strtotime($date));

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);

$sql = "INSERT INTO findlater (`id`, `carrier`,`type`,`latitude`,`longitude`,`firstseen`,`bio`,`bands`,`city`,`zip`,`state`,`address`)
                      VALUES (
                        '".mysqli_real_escape_string($conn, $id)."',
                        '".mysqli_real_escape_string($conn, $carrier)."',
                        '".mysqli_real_escape_string($conn, $type)."',
                        '".mysqli_real_escape_string($conn, $latitude)."',
                        '".mysqli_real_escape_string($conn, $longitude)."',
                        '".mysqli_real_escape_string($conn, $firstseen)."',
                        '".mysqli_real_escape_string($conn, $bio)."',
                        '".mysqli_real_escape_string($conn, $bands)."',
                        '".mysqli_real_escape_string($conn, $city)."',
                        '".mysqli_real_escape_string($conn, $zip)."',
                        '".mysqli_real_escape_string($conn, $state)."',
                        '".mysqli_real_escape_string($conn, $address)."');  ";

if (mysqli_query($conn, $sql)) {
    echo '<meta http-equiv="refresh" content="0; url=../cm/">';
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);


?>
<!doctype html>
<html lang="en-us">
<head>
<meta charset="utf-8">
</head>
<body style="zoom: 100%">
</body>
</html>
