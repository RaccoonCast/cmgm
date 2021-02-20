<?php
// Cast's Awesome API (CAAPI)
include '../includes/functions/sqlpw.php';

$database_get_list = $_GET['properties'];
$database_id = $_GET['id'];

$sql = "SELECT $database_get_list FROM database_db WHERE id=" . "$database_id" . "";

$arr = array();
if ($result = $conn->query($sql)) {

    while($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $arr[] = $row;
    }
     echo json_encode($arr);
}

$result->close(); $conn->close();
?>
