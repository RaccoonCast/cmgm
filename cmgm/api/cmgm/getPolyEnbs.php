<?php
//  cody and alps' purple iphones (CAAPI)
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');

header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');

include '../../includes/functions/sqlpw.php'; // doesn't call native

//  cody and alps' purple iphones (CAAPI)
$db_get_list = "plmn,rat,enb,tac,latitude,longitude,cells";
$db_vars = "enb > 0";

foreach($_GET as $key => $value){
  if ($key == "plmn") {
    continue;
  }
  if ($key == "latitude" OR $key == "longitude" OR $key == "limit" OR $key == "properties" OR $key == "max_distance") {
    ${$key} = preg_replace('/[^a-z0-9_\-.,]/', '', $value);;
  } else {
    if ($key == "rat" && $value == "0") continue;
    // this code lets you add things to the search string, like WHERE cellsite_type = "monopalm" by amending &cellsite_type=monopalm.
      $db_get_list = $db_get_list . "," . $key;

    $db_vars = preg_replace('/[^a-zA-Z0-9_]/', '', $key) . ' = "'.preg_replace('/[^a-zA-Z0-9_,\-]/', '', $value).'" AND ' . $db_vars; 
  }
}

// PLMN filter
if (!empty($_GET['plmn'])) {
    $plmn = $_GET['plmn'];

    if (strpos($plmn, ',') !== false) {
        // Multiple PLMNs (comma-separated)

        $plmnArray = array_map(
            fn($v) => "'" . preg_replace('/[^0-9]/', '', trim($v)) . "'",
            explode(',', $plmn)
        );

        // Remove empty values
        $plmnArray = array_filter($plmnArray);

        if (!empty($plmnArray)) {
            $db_vars .= " AND plmn IN (" . implode(',', $plmnArray) . ")";
        }

    } else {
        // Single PLMN
        $plmn = preg_replace('/[^0-9]/', '', $plmn);
        $db_vars .= " AND plmn = $plmn";
    }
}

if (empty($limit)) $limit = "110";

$sql = "SELECT DISTINCT $db_get_list, (3959 * ACOS(COS(RADIANS($latitude)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS($longitude)) + SIN(RADIANS($latitude)) * SIN(RADIANS(latitude)))) AS DISTANCE FROM local_poly_enbs WHERE $db_vars ORDER BY DISTANCE LIMIT $limit";

$result = $conn->query($sql);

// Create an array to hold carrier-specific results
$carrierGroups = array();

// Loop through the results and organize them by carrier type
while ($row = $result->fetch_assoc()) {
    $distance = $row["DISTANCE"];
    $carrierType = $row['plmn'];
    unset($row["DISTANCE"]);
    unset($row['plmn']); 

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
