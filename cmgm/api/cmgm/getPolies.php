<?php
//  cody and alps' purple iphones (CAAPI)
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');

header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');

include '../../includes/functions/sqlpw.php'; // doesn't call native

//  cody and alps' purple iphones (CAAPI)
$db_vars = "provider_source IS NOT NULL";
$max_distance = "5";
$num = 0;
if (isset($_GET['plmn']) && is_numeric($_GET['plmn'])) $plmn = $_GET['plmn'];
if (isset($_GET['rat']) && strlen($_GET['rat']) <= 3) $rat = $_GET['rat'];

foreach($_GET as $key => $value){
  if ($key == "latitude" OR $key == "longitude" OR $key == "fake_limit" OR $key == "properties" OR $key == "showsql") {
    ${$key} = $value;
  } else {
    $db_vars = preg_replace('/[^a-zA-Z0-9_]/', '', $key) . ' = "'.preg_replace('/[^a-zA-Z0-9_]/', '', $value).'" AND ' . $db_vars;
  }
}

if (empty($fake_limit)) $fake_limit = "150";
$sql = "
-- First: get the 50 closest rows by distance
WITH base_results AS (
  SELECT *, 
    (3959 * ACOS(
      COS(RADIANS($latitude)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS($longitude)) +
      SIN(RADIANS($latitude)) * SIN(RADIANS(latitude))
    )) AS distance
  FROM local_poly
  WHERE $db_vars
  ORDER BY distance
  LIMIT $fake_limit
),

-- Second: extract the enbs from those 50
selected_enbs AS (
  SELECT DISTINCT enb FROM base_results
)

-- Finally: return all rows matching those enbs
SELECT lp.*
FROM local_poly lp
JOIN selected_enbs se ON lp.enb = se.enb
WHERE $db_vars
ORDER BY lp.enb, lp.cell;
";
if (isset($_GET['showsql'])) {
  echo $sql;
  die;
}
$result = $conn->query(query: $sql);

// Create an array to hold the latitude and longitude values
$response_body = array();

// Loop through the results and add them to the array
while ($row = $result->fetch_assoc()) {
  if (empty($row['latitude'])) continue;
  if (empty($row['longitude'])) continue;
  if (($row['enb'] > 950000) && ($row['enb'] < 959999) && ($plmn == 310410)) continue;
  if (($row['enb'] > 990000) && ($row['enb'] < 999999) && ($plmn == 310410)) continue;

    $response_body[] = array(
      "plmn" => $row["plmn"],
      "cell" => $row["cell"],
      "cell_id" => $row["cell_id"],
      "enb" => $row["enb"],
      "rat" => $row["rat"],
      "latitude" => $row["latitude"],
      "longitude" => $row["longitude"]
    );

}

// Output the organized array
echo json_encode($response_body);

$result->close(); $conn->close();

?>
