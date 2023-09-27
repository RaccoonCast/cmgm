<?php
function query_db($conn,$id,$fields_array) {
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $list = implode(', ', array_keys($fields_array));

    $sql = "SELECT $list FROM db WHERE id = $id";

    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $dbResponse = $result->fetch_assoc();
        $conn->close();
        return($dbResponse);
    } else {
        noRecordFound();
    }
}
?>