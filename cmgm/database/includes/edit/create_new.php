<?php
$date_added = date("Y-m-d H:i:s");
$newQuery = "INSERT INTO db (id, date_added, created_by, coords) VALUES ('$id', '$date_added', '$username', ST_GeomFromText('POINT($latitude $longitude)', 4326));";
mysqli_query($conn, $newQuery);

?>
