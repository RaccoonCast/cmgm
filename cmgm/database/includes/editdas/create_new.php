<?php
$date_added = date("Y-m-d");
mysqli_query($conn, "INSERT INTO db (id,date_added) VALUES ('$id','$date_added');");
?>