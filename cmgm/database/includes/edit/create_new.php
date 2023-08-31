<?php
$date_added = date("Y-m-d");
mysqli_query($conn, "INSERT INTO db (id,date_added,created_by) VALUES ('$id','$date_added','$username');");
?>
