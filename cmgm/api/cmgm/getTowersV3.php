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
$max_distance = "5";
$num = 0;
if (isset($_GET['properties'])) $db_get_list = preg_replace('/[^a-zA-Z0-9_,]/', '', $_GET['properties']);
if (isset($_GET['max_distance'])) $max_distance = preg_replace('/[^0-9]/', '', $_GET['max_distance']);

foreach($_GET as $key => $value){
  if ($key == "latitude" OR $key == "longitude" OR $key == "limit" OR $key == "properties" OR $key == "max_distance") {
    ${$key} = preg_replace('/[^a-z0-9_,]/', '', $value);;
  } else {
    // this code lets you add things to the search string, like WHERE cellsite_type = "monopalm" by amending &cellsite_type=monopalm.
      $db_get_list = $db_get_list . "," . $key;

    $db_vars = preg_replace('/[^a-zA-Z0-9_]/', '', $key) . ' = "'.preg_replace('/[^a-zA-Z0-9_]/', '', $value).'" AND ' . $db_vars; 
  }
}

if (empty($limit)) $limit = "550";

$sql = "SELECT DISTINCT $db_get_list, (3959 * ACOS(COS(RADIANS($latitude)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS($longitude)) + SIN(RADIANS($latitude)) * SIN(RADIANS(latitude)))) AS DISTANCE FROM db WHERE $db_vars HAVING DISTANCE <= $max_distance ORDER BY DISTANCE LIMIT $limit";

$result = $conn->query($sql);

// Create an array to hold carrier-specific results
$carrierGroups = array();

// Loop through the results and organize them by carrier type
while ($row = $result->fetch_assoc()) {
  $distance = $row["DISTANCE"];
  unset($row["edit_userid"]);
  unset($row["edit_lock"]);
  unset($row["DISTANCE"]);

  // Determine carrier type
  $carrierType = isset($row['carrier']) ? $row['carrier'] : 'unknown';
  unset($row["carrier"]);
  
  // Remove empty values directly within the loop
  foreach ($row as $key => $value) {
        if (empty($value)) {
            unset($row[$key]);
        }
  }

  // Initialize the array for this carrier type if not already set
  if (!isset($carrierGroups[$carrierType])) {
      $carrierGroups[$carrierType] = array();
  }

  // Append the row data to the carrier type array
  $carrierGroups[$carrierType][] = $row;
}

// Output the organized array
echo json_encode($carrierGroups);

$result->close(); $conn->close();

?>
