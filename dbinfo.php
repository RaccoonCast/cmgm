<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Findlater Database</title>
  <?php include 'functions.php';?>
  <?php if(isMobile()){
	  echo '<link rel="stylesheet" href="styles/m-dbinfo.css">';
  } else {
	  echo '<link rel="stylesheet" href="styles/d-dbinfo.css">';
  }
?>

</head>

<body style="#page">
<?php
$servername = '127.0.0.1';
$username = 'rooter';
$password = 'My$QLP@$$w0rd';
$dbname = 'cmgm';
$conn = mysqli_connect($servername, $username, $password, $dbname);

$latitude  = file_get_contents('dustbin\latitude.txt');
$longitude = file_get_contents('dustbin\longitude.txt');

$sql = "SELECT DISTINCT *, (3959 * ACOS(COS(RADIANS($latitude)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS($longitude)) + SIN(RADIANS($latitude)) * SIN(RADIANS(latitude)))) AS DISTANCE FROM findlater ORDER BY distance";
$result = mysqli_query($conn, $sql); // First parameter is just return of "mysqli_connect()" function

echo "<table class='TFtable' border='1'>";
echo '<tr><td>eNB ID';
echo '<td>Carrier';
echo '<td>Type';
echo '<td>Latitude';
echo '<td>Longitude';
echo '<td>First Seen';
echo '<td>Band(s)';
echo '<td>Citiy';
echo '<td>Zip';
echo '<td>State';
echo '<td>Address';
echo '<td>Link';
echo '<td>Bio';
echo '</tr>';
while ($row = mysqli_fetch_assoc($result)) { // Important line !!! Check summary get row on array ..
    echo "<tr>";
    foreach ($row as $field => $value) { // I you want you can right this line like this: foreach($row as $value) {
            		if(strpos($value, 'https://') !== 0) {
             echo "<td>" . $value . "</td>"; // I just did not use "htmlspecialchars()" function.
            } else {
				echo '<td><a href="'.$value.'" target="_blank">Google Maps</a></td>';

            }

    }
    echo "</tr>";
}
echo "</table>";
?>
</body>
</html>
