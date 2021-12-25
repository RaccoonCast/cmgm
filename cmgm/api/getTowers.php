<?php
// Cast's Awesome API (CAAPI)
$db_get_list = "id,latitude,longitude";
$db_vars = "id > 0";
include '../includes/functions/sqlpw.php';
include '../includes/useridsys/native.php';

foreach($_GET as $key => $value){
  if ($key == "latitude" OR $key == "longitude" OR $key == "limit") {
    ${$key} = $value;
  } else {
    $db_get_list = $db_get_list . "," . $key;
    $db_vars = $key . ' = "'.$value.'" AND ' . $db_vars;
  }
}

if (empty($limit)) $limit = "1";

$sql = "SELECT DISTINCT $db_get_list, (3959 * ACOS(COS(RADIANS($latitude)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS($longitude)) + SIN(RADIANS($latitude)) * SIN(RADIANS(latitude)))) AS DISTANCE FROM db WHERE $db_vars ORDER BY distance LIMIT $limit";

$arr = array();
if ($result = $conn->query($sql)) {
  if ($result->num_rows > 0) {
              $arr = [];
              $inc = 0;
              while ($row = $result->fetch_assoc()) {
                  # code...
                  $jsonArrayObject = (array('lat' => $row["latitude"], 'lon' => $row["longitude"], 'id' => $row["id"]));
                  $arr[$inc] = $jsonArrayObject;
                  $inc++;
              }
              $json_array = json_encode($arr);
              echo $json_array;
          }
          else{
              echo "0 results";
          }
}

$result->close(); $conn->close();
?>
