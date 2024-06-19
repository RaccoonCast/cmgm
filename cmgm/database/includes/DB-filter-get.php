<?php
if (isset($_POST['latitude'])) include "DB-filter-post.php";
foreach($_GET as $key => $value){
$key = strtolower($key);
@$fs = @$id = str_replace(' ', '', $value);
@$trimChar = substr($value, 1);

if (!empty($value) OR $value == "NULL" OR $value == "0") {
  if ($key != "latitude" && $key != "longitude" && $key != "zoom") @$url_suffix = @$url_suffix . "&" . $key . "=" . $value;
  if ($value == "NULL") $value = null;
  if ($value == "!NULL") $trimChar = null;
  include "DB-filter.php";
 }
}
if (!empty($db_vars)) $db_vars = substr(strstr($db_vars," "), 1);
@$db_vars = str_replace('WHERE AND', 'WHERE', @$db_vars);
 ?>
