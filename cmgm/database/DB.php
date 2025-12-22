<?php include '../functions.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <script src="../js/redir.js"></script>
  <script src="../js/copyToClipboard.js"></script>
  <?php
  include "../includes/functions/headhtml.php";
  if (!isset($_GET['latitude'])) $latitude = $default_latitude;
  if (!isset($_GET['longitude'])) $longitude = $default_longitude;
  include "includes/DB-filter-get.php";
  include "../includes/link-conversion-and-handling/function_goto.php";
?>
</head><?php include "includes/DB.php" ?><?php include "includes/footer.php" ?>
