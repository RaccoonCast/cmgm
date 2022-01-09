<!doctype html>
<html lang="en">
<head>
  <?php
  include '../functions.php';
  if (!isset($_GET['latitude'])) $latitude = $default_latitude;
  if (!isset($_GET['longitude'])) $longitude = $default_longitude;
  include "includes/DB-filter.php";
  ?>
</head>
<body>
<?php

$sql = "SELECT DISTINCT street_view_a,
(3959 * ACOS(COS(RADIANS($latitude)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS($longitude)) + SIN(RADIANS($latitude)) * SIN(RADIANS(latitude))))
AS DISTANCE FROM db ".@$db_vars." ORDER BY distance LIMIT $limit";
if (isset($_GET['showsql'])) echo $sql . "<br>";
$result = mysqli_query($conn,$sql);
$counter=0; $counter_10=-1;
while($row  = $result->fetch_assoc()) {
    foreach ($row as $key => $value) {
      $$key = $value;
      if (!empty($street_view_a)) {
        $counter++;  $counter_10++;
        if ($counter_10 == "10") {
          echo "<br>";
          $counter_10 = 0;
        }
        if (strlen($counter) == 1) $counter = "00" . $counter;
        if (strlen($counter) == 2) $counter = "0" . $counter;
        echo '<a href="https://'.str_replace("https://", "",$sv_a).'">'.$counter.'</a> ';
      }
  }
}
?>
</tbody>
</table>
<?php include "includes/footer.php"; ?>
</body>
</html>
