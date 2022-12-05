<?php
header('Content-type: application/json');
$api_called = "true";
//  cody and alps' purple iphones (CAAPI)
include '../includes/functions/sqlpw.php';
include '../includes/useridsys/native.php';

if (isset($_GET['properties'])) {
  $database_get_list = $_GET['properties'];
} else {
  $database_get_list = "*";
}

$database_id = $_GET['id'];

$sql = "SELECT $database_get_list, NULL AS edit_userid, NULL as edit_lock FROM db WHERE id=" . "$database_id" . "";

$arr = array();
if ($result = $conn->query($sql)) {

    while($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $arr[] = $row;
    }
     echo json_encode($arr);
}

$result->close(); $conn->close();
?>
