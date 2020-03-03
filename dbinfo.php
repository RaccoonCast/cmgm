<!doctype html>
<html lang="en">
<head>
  <title>Findlater Database</title>
  <?php include 'functions.php';?>
</head>
<body>
<?php
$conn = mysqli_connect($servername, $username, $password, $dbname);

$limit = 10000;

if (isset($_GET['latitude'])) $latitude = $_GET['latitude'];
if (isset($_GET['longitude'])) $longitude = $_GET['longitude'];
if (isset($_GET['limit'])) $limit = $_GET['limit'];


$sql = "SELECT DISTINCT *, (3959 * ACOS(COS(RADIANS($latitude)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS($longitude)) + SIN(RADIANS($latitude)) * SIN(RADIANS(latitude)))) AS DISTANCE FROM findlater ORDER BY distance
LIMIT $limit ";
$result = mysqli_query($conn, $sql); // First parameter is just return of "mysqli_connect()" function

?>
<table border="1">
<thead>
<tr>
<th>eNB ID</th>
<th>Carrier</th>
<th>Type</th>
<th>First Seen</th>
<th>Band(s)</th>
<!--
<th>Citiy</th>
<th>Zip</th>
<th>State</th>
-->
<th>Address</th>
<th>Bio</th>
</tr>
</thead>
<tbody>
<?php
while ($row = mysqli_fetch_assoc($result)) { // Important line !!! Check summary get row on array ..
  $colCount = 1;
    echo "<tr>";
    foreach ($row as $field => $value) {

      $sepCount = ($colCount++);

                  switch ($sepCount) {
                      case 4:
                          $lat = $value;
                          break;
                      case 5:
                          $long = $value;
                          break;
                      case 6:
                          echo '<td class="firstseen">' . $value . '</td>';
                          break;
                      case 8:
                      $city = $value;
                          break;
                      case 9:
                      $zip = $value;
                          break;
                      case 10:
                      $state = $value;
                          break;
                      case 11:
                          $address = $value;
                          echo nl2br('<td class="address"><a href="https://cmgm.gq/cm/hub.php?latitude='.$lat.'&longitude='.$long.'">' . $address . ' <br>' . $city . ', ' . $state . ' ' . $zip . '</td></a>');
                          break;
                      case 13:
                          break;
                      default:
                          echo nl2br("<td>" . $value . "</td>");
                          break;
                      case 2:
                          echo nl2br("<td>" . $value . "</td>");
                          break;
                  }
            }
echo "</tr>";
    }
?>
</tbody>
</table>
</body>
</html>
