<?php
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

$sql = "SELECT * FROM database_db WHERE (carrier = '$carrier' AND id = '$id')";

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$result = mysqli_query($conn, $sql);

if (!mysqli_num_rows($result) == 0) {
        $dont_create = 'true';
        mysqli_close($conn);
        echo '<meta http-equiv="refresh" content="0;URL=already-exists.php" /> ';
} else {
    $dont_create = 'false';
    mysqli_close($conn);
}
?>
