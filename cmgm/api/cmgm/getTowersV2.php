<?php
//  cody and alps' purple iphones (CAAPI)
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');

header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');

include '../../includes/functions/sqlpw.php'; // doesn't call native

//  cody and alps' purple iphones (CAAPI)
$db_get_list = "id,carrier,latitude,longitude,status,tags";
$db_vars = "id > 0";


foreach($_GET as $key => $value){
  $clean_value = preg_replace('/[^0-9-.]/', '', $value);

  if ($key == "boundsNELat" OR $key == "boundsNELon" OR $key == "boundsSWLat" OR $key = 'boundsSWLon' OR $key = 'limit') {
    ${$key} = $clean_value;
  } else {
    // this code lets you add things to the search string, like WHERE cellsite_type = "monopalm" by amending &cellsite_type=monopalm.
      $db_get_list = $db_get_list . "," . $key;

    $db_vars = preg_replace('/[^a-z0-9_\-.,]/', '', $key) . ' = "'.preg_replace('/[^a-zA-Z0-9_]/', '', $value).'" AND ' . $db_vars;
  }
}

if (empty($limit)) $limit = "550";
if (!isset($searchPolygon)) $searchPolygon = "POLYGON(($boundsSWLat $boundsSWLon, $boundsNELat $boundsSWLon, $boundsNELat $boundsNELon, $boundsSWLat $boundsNELon, $boundsSWLat $boundsSWLon))";
$whereFiltersLocation = " AND MBRWithin(coords, ST_GeomFromText('$searchPolygon', 4326)) ";
$sql = "SELECT DISTINCT $db_get_list FROM db WHERE $db_vars$whereFiltersLocation";

$result = $conn->query($sql);

// Create an array to hold the latitude and longitude values
$locations = array();

// Loop through the results and add them to the array
while ($row = $result->fetch_assoc()) {
    $locations[] = array(
      "id" => $row["id"],
      "carrier" => $row["carrier"],
      "latitude" => $row["latitude"],
      "longitude" => $row["longitude"],
      "cellsite_type" => @$row["cellsite_type"],
      "concealed" => @$row["concealed"],
      "status" => $row["status"],
      "tags" => $row["tags"]
    );
}
echo json_encode($locations);

$result->close(); $conn->close();

?>
