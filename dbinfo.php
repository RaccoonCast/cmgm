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
echo '<div class="start"></td>';
echo '<tr><td class="top">eNB ID</td>';
echo '<td class="top">Carrier</td>';
echo '<td class="top">Type</td>';
echo '<td class="top">Latitude and Longitude</td>';
echo '<td class="top">First Seen</td>';
echo '<td class="top">Band(s)</td>';
echo '<td class="top">Citiy</td>';
echo '<td class="top">Zip</td>';
echo '<td class="top">State</td>';
echo '<td class="top">Address</td>';
echo '<td class="top">Bio</td>';
echo '</tr>';
echo '</div>';
while ($row = mysqli_fetch_assoc($result)) { // Important line !!! Check summary get row on array ..
  $colCount = 1;
    echo "<tr>";
    foreach ($row as $field => $value) {

      $sepCount = ($colCount++);

                  switch ($sepCount) {
                      case 1:
                          echo nl2br("<td>" . $value . "</td>");
                          break;
                      case 2:
                          echo nl2br("<td>" . $value . "</td>");
                          break;
                      case 3:
                          echo nl2br("<td>" . $value . "</td>");
                          break;
                      case 4:
                          $lat = $value;
                          break;
                      case 5:
                          $long = $value;
                          $url = "http://www.google.com/maps/@?api=1&map_action=map&center=$lat,$long&zoom=20&basemap=satellite";
                          echo '<td><a href="'.$url.'" target="_blank">'.$lat.','.$long.'</a></td>';
                          break;
                      case 6:
                          echo nl2br("<td>" . $value . "</td>");
                          break;
                      case 7:
                          echo nl2br("<td>" . $value . "</td>");
                          break;
                      case 8:
                          echo nl2br("<td>" . $value . "</td>");
                          break;
                      case 9:
                          echo nl2br("<td>" . $value . "</td>");
                          break;
                      case 10:
                          echo nl2br("<td>" . $value . "</td>");
                          break;
                      case 11:
                          echo nl2br("<td>" . $value . "</td>");
                          break;
                      case 12:
                          echo nl2br("<td>" . $value . "</td>");
                          break;
                      case 13:
                          break;
                  }
            }

    }
    echo "</tr>";
echo "</table>";
?>
</body>
</html>
