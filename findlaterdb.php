<?php

// MySQL Login info

$servername = '127.0.0.1';
$username = 'rooter';
$password = 'My$QLP@$$w0rd';
$dbname = 'cmgm';

// Get lat & long from dustbin

if (isset($_GET['latitude'])) $latitude = $_GET['latitude'];
if (isset($_GET['longitude'])) $longitude = $_GET['longitude'];

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

// Extract zip

$addressComponents = $response->results[0]->address_components;
foreach ($addressComponents as $addrComp) {
    if ($addrComp->types[0] == 'postal_code') {
        $zip = $addrComp->long_name;
    }
}

// Extract address number

$addressComponents = $response->results[0]->address_components;
foreach ($addressComponents as $addrComp) {
    if ($addrComp->types[0] == 'street_number') {
        $number = $addrComp->short_name;
    }
}
// Extract street name

$addressComponents = $response->results[0]->address_components;
foreach ($addressComponents as $addrComp) {
    if ($addrComp->types[0] == 'route') {
        $name = $addrComp->short_name;
    }
}

$address = "$number $name";

// Extract city (locality)

$addressComponents = $response->results[0]->address_components;
foreach ($addressComponents as $addrComp) {
    if ($addrComp->types[0] == 'locality') {
        $city = $addrComp->short_name;
    }
}

// Extract state

$addressComponents = $response->results[0]->address_components;
foreach ($addressComponents as $addrComp) {
    if ($addrComp->types[0] == 'administrative_area_level_1') {
        $state = $addrComp->short_name;
    }
}

// Get some data from the form

$id = $_GET['field3'];
$carrier = $_GET['field2'];
$type = $_GET['field1'];

$firstseen = $_GET['field4'];
$firstseen2 = $_GET['field7'];

if (substr("$firstseen2", 0, 2) === '20') {
    $firstseen = $firstseen2;
} else {
    if (empty($firstseen2)) {
        $date = str_replace('/"', '-', $firstseen);
        $newDate = date("Y/m/d", strtotime($date));
        $firstseen = $newDate;
    } else {
        if (substr("$firstseen2", 1, 2) === 'at' || substr("$firstseen2", 1, 2) === 'on' || substr("$firstseen2", 1, 2) === 'ue' || substr("$firstseen2", 1, 2) === 'ed' || substr("$firstseen2", 1, 2) === 'hu' || substr("$firstseen2", 1, 2) === 'ri' || substr("$firstseen2", 1, 2) === 'Sat') {
            $firstseen2 = substr($firstseen2, 5);
        }
        if (substr("$firstseen2", 1, 2) === 'an') {
            $month = '1';
        }
        if (substr("$firstseen2", 1, 2) === 'eb') {
            $month = '2';
        }
        if (substr("$firstseen2", 1, 2) === 'ar') {
            $month = '3';
        }
        if (substr("$firstseen2", 1, 2) === 'pr') {
            $month = '4';
        }
        if (substr("$firstseen2", 1, 2) === 'ay') {
            $month = '5';
        }
        if (substr("$firstseen2", 1, 2) === 'un') {
            $month = '6';
        }
        if (substr("$firstseen2", 1, 2) === 'ul') {
            $month = '7';
        }
        if (substr("$firstseen2", 1, 2) === 'ug') {
            $month = '8';
        }
        if (substr("$firstseen2", 1, 2) === 'ep') {
            $month = '9';
        }
        if (substr("$firstseen2", 1, 2) === 'ct') {
            $month = '10';
        }
        if (substr("$firstseen2", 1, 2) === 'ov') {
            $month = '11';
        }
        if (substr("$firstseen2", 1, 2) === 'ec') {
            $month = '12';
        }
        $day = substr(strstr("$firstseen2", " "), 1, -5);
        if (substr("$day", -1) === ',') {
            $day = substr($day, 0, -1);
        }
        $year = substr("$firstseen2", -4);
        $firstseen = "$year-$month-$day";
    }
}

$bands = $_GET['field5'];
$bio = $_GET['field6'];

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "INSERT INTO findlater (`id`, `carrier`,`type`,`latitude`,`longitude`,`firstseen`,`bio`,`bands`,`city`,`zip`,`state`,`address`) VALUES ('$id','$carrier','$type','$latitude','$longitude','$firstseen','".mysqli_real_escape_string($conn, $bio)."','$bands','$city','$zip','$state','$address');  ";

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
<title>Evil CM</title>
<meta charset="utf-8">
</head>
<body style="zoom: 100%">
</body>
</html>
