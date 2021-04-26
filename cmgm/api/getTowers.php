<?php
// Cast's Awesome API (CAAPI)
$db_get_list = "id,latitude,longitude";
$db_variables = "id > 0";
include '../includes/functions/sqlpw.php';
include '../includes/useridsys/native.php';

foreach($_GET as $key => $value){
  if ($key == "latitude" OR $key == "longitude" OR $key == "limit") {
    ${$key} = $value;
  } else {
    $db_get_list = $db_get_list . "," . $key;
    $db_variables = $key . ' = "'.$value.'" AND ' . $db_variables;
  }
}

if (empty($limit)) $limit = "1";

$sql = "SELECT DISTINCT $db_get_list, (3959 * ACOS(COS(RADIANS($latitude)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS($longitude)) + SIN(RADIANS($latitude)) * SIN(RADIANS(latitude)))) AS DISTANCE FROM database_db WHERE $db_variables ORDER BY distance LIMIT $limit";

$arr = array();
if ($result = $conn->query($sql)) {

    while($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $arr[] = $row;
    }
     echo json_encode($arr);
}

$result->close(); $conn->close();
?>
