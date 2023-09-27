<!doctype html>
<html lang="en-us">
   <head>
      <?php include '../functions.php'; ?>
        <link rel="stylesheet" href="../database/styles/Edit/main.css">
        <link rel="stylesheet" href="../database/styles/Edit/black.css">
        <link rel="stylesheet" href="../database/styles/Edit/desktop.css">
   </head>
<?php
ini_set ('display_errors', 1);
ini_set ('display_startup_errors', 1);
error_reporting (E_ALL);

$id = preg_replace("/[^0-9]/", '', $_GET['id']);

?> 

<?php
include "../includes/functions/tower_types.php";
include "query_db.php";
include "fields_array.php";
include "form.php";

$data = query_db($conn,$id);
generateForm($data,$fields_array);
?>