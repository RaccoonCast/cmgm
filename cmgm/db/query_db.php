<?php
function query_db($conn,$id) {
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "SELECT id,status,carrier,concealed,cellsite_type,lte_1,lte_2,lte_3,lte_4,lte_5,lte_6,lte_7,lte_8,lte_9,nr_1,nr_2,nr_3,nr_4 FROM db WHERE id = $id";
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