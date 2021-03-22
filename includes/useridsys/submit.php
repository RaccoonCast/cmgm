z<?php
// PRESET VALUES
$userID = substr(str_shuffle(md5(time())),0,32);
$username = $_POST['username'];
// FORM VALUES
$default_carrier = $_POST['default_carrier'];
$default_latitude = $_POST['default_latitude'];
$default_longitude = $_POST['default_longitude'];
$theme = $_POST['theme'];
$gmaps_api_key = $_POST['gmaps_api_key'];
$pin = $_POST['pin'];
$sql = "INSERT INTO userID (userID, username, pin, userIP, gmaps_api_key, default_carrier, default_latitude, default_longitude, theme)
                VALUES (
                  '".mysqli_real_escape_string($conn, $userID)."',
                  '".mysqli_real_escape_string($conn, $username)."',
                  '".mysqli_real_escape_string($conn, $pin)."',
                  '".mysqli_real_escape_string($conn, $userIP)."',
                  '".mysqli_real_escape_string($conn, $gmaps_api_key)."',
                  '".mysqli_real_escape_string($conn, $default_carrier)."',
                  '".mysqli_real_escape_string($conn, $default_latitude)."',
                  '".mysqli_real_escape_string($conn, $default_longitude)."',
                  '".mysqli_real_escape_string($conn, $theme)."');  ";
                  mysqli_query($conn, $sql);
                  mysqli_close($conn);
                  setcookie("userID", $userID, time()+2147483647);
                  ?>
