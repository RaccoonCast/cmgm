<?php
$ip = $_SERVER["REMOTE_ADDR"];
$sql = "SELECT * FROM userID WHERE userIP = '$ip'";
$result = mysqli_query($conn,$sql);

while($row = $result->fetch_assoc()) {
    foreach ($row as $key => $value) {
        $$key = $value;
        }
      }
if (isset($gmaps_api_key_access)) if ($gmaps_api_key_access == 'true') $maps_api_key = file_get_contents($siteroot . "/maps_api_key.hiddenpass", true);

if (!isset($userIP)) {
  $userID = substr(str_shuffle(md5(time())),0,32);
  $username = $userID;
  $userIP = $_SERVER["REMOTE_ADDR"];
  $gmaps_api_key_access = "false";
  $default_carrier = "";
  $default_latitude = "38.89951743540001";
  $default_longitude = "-77.03655226691319";
  $theme = "white";
  $gmaps_util = "0";

  $sql = "INSERT INTO userID (userID, username, userIP, gmaps_api_key_access, default_carrier, default_latitude, default_longitude, theme, gmaps_util)
                  VALUES (
                    '".mysqli_real_escape_string($conn, $userID)."',
                    '".mysqli_real_escape_string($conn, $username)."',
                    '".mysqli_real_escape_string($conn, $userIP)."',
                    '".mysqli_real_escape_string($conn, $gmaps_api_key_access)."',
                    '".mysqli_real_escape_string($conn, $default_carrier)."',
                    '".mysqli_real_escape_string($conn, $default_latitude)."',
                    '".mysqli_real_escape_string($conn, $default_longitude)."',
                    '".mysqli_real_escape_string($conn, $theme)."',
                    '".mysqli_real_escape_string($conn, $gmaps_util)."');  ";
                    mysqli_query($conn, $sql);
                    mysqli_close($conn);
}
?>
