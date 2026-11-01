<?php
//  cody and alps' purple iphones (CAAPI)
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');
header('Content-Type: application/json');

include '../../includes/functions/sqlpw.php'; // doesn't call native

//  cody and alps' purple iphones (CAAPI)
if (isset($_GET['plmn']) && is_numeric($_GET['plmn']) && (($_GET['rat'] == "LTE") OR ($_GET['rat'] == "NR")) && isset($_GET['enb']) && is_numeric($_GET['enb'])) {
    $plmn = $_GET['plmn'];
    $rat = $_GET['rat'];
    $enb = $_GET['enb'];
} else {
    die("Invalid input.");
}


$conn->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, true);

$stmt = $conn->prepare("SELECT enb, cell AS cells, cell_id, plmn, rat, tac, latitude, longitude, date_of_info 
                        FROM local_poly_beta 
                        WHERE plmn = ? AND rat = ? AND eNB = ? 
                        AND latitude <> 0.0 AND longitude <> 0.0");

$stmt->bind_param("isi", $plmn, $rat, $enb);
$stmt->execute();
$result = $stmt->get_result();

echo '[';
$first = true;
while ($row = $result->fetch_assoc()) {
    if (!$first) echo ',';
    echo json_encode($row);
    $first = false;
}
echo ']';

$stmt->close();
$conn->close();
