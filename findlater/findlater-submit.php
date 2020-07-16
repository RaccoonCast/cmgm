<?php
include "../functions.php";

$type = $_GET['type'];
$carrier = $_GET['carrier'];
$id = $_GET['id'];
$bands = $_GET['bands'];
$firstseen = $_GET['date'];
$bio = $_GET['bio'];

if (!empty($firstseen)) {
  $date = str_replace('/"', '-', $firstseen);
  $firstseen = date("Y/m/d", strtotime($date));
}

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (empty($firstseen)) {
  $firstseen = '0000-00-00';
}

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
echo $sql;
if (mysqli_query($conn, $sql)) {
    echo '<meta http-equiv="refresh" content="2;URL=../" /> ';
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>
