<!doctype html>
<html lang="en">
<head>
  <script src="../js/redir.js"></script>
  <script src="../js/copyToClipboard.js"></script>
  <?php
  include '../functions.php';
  if (!isset($_GET['latitude'])) $latitude = $default_latitude;
  if (!isset($_GET['longitude'])) $longitude = $default_longitude;
  include "includes/DB-filter-post.php";
  include "../includes/link-conversion-and-handling/function_goto.php";

  if (!isset($limit)) $limit = "100";
  $sql = "SELECT DISTINCT *, (3959 * ACOS(COS(RADIANS($latitude)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS($longitude)) + SIN(RADIANS($latitude)) * SIN(RADIANS(latitude)))) AS DISTANCE FROM db ".@$db_vars." ORDER BY distance LIMIT $limit";
  if (isset($_GET['showsql'])) echo $sql;
  $result = mysqli_query($conn,$sql);
  if (mysqli_num_rows($result) == "1") {
    while($row = $result->fetch_assoc()) {
    foreach ($row as $key => $value) {
      $$key = $value;
      if ($key == "DISTANCE") { redir("Edit.php?id=$id","0"); }
    }
  }
  }
  if (mysqli_num_rows($result) == "0") {
    echo "<br> No results found.";
    redir("Search.php?latitude=$latitude&longitude=$longitude","1");
  }
  ?>
</head><?php include "includes/DB.php" ?>
