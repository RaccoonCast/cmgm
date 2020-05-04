<?php
include '../functions.php';
if (isset($_GET['databaseID'])) $row_id = $_GET['databaseID'];
$conn = mysqli_connect($servername, $username, $password, $dbname);

$sql = "DELETE FROM `cmgm`.`database` WHERE  `row_id`=" . $row_id . ";";
mysqli_query($conn, $sql); // First parameter is just return of "mysqli_connect()" function
//header('Location: https://cmgm.gq/');
 ?>
