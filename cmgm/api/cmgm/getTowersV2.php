<?php
//  cody and alps' purple iphones (CAAPI)
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');

header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');

include '../../includes/functions/sqlpw.php'; // doesn't call native

//  cody and alps' purple iphones (CAAPI)
$db_get_list = "id,carrier,latitude,longitude,cellsite_type,concealed,status,tags";
$db_vars = "id > 0";


foreach($_GET as $key => $value){
  if ($key == "latitude" OR $key == "longitude" OR $key == "limit") {
    ${$key} = $value;
  } else {
    // this code lets you add things to the search string, like WHERE cellsite_type = "monopalm" by amending &cellsite_type=monopalm.
      $db_get_list = $db_get_list . "," . $key;

    $db_vars = $key . ' = "'.$value.'" AND ' . $db_vars;
  }
}

if (empty($limit)) $limit = "550";

$sql = "SELECT DISTINCT $db_get_list, (3959 * ACOS(COS(RADIANS($latitude)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS($longitude)) + SIN(RADIANS($latitude)) * SIN(RADIANS(latitude)))) AS DISTANCE FROM db WHERE $db_vars ORDER BY distance LIMIT $limit";

$result = $conn->query($sql);

// Create an array to hold the latitude and longitude values
$locations = array();

// Loop through the results and add them to the array
while ($row = $result->fetch_assoc()) {
  if ($row["DISTANCE"] < 5) {
    $locations[] = array(
      "name" => $row["id"],
      "carrier" => $row["carrier"],
      "latitude" => $row["latitude"],
      "longitude" => $row["longitude"],
      "cellsite_type" => $row["cellsite_type"],
      "concealed" => $row["concealed"],
      "status" => $row["status"],
      "tags" => $row["tags"]
    );
  }
}
echo json_encode($locations);

$result->close(); $conn->close();

?>
