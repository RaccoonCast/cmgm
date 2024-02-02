<?php
foreach($_POST as $key => $value){
@$fs = @$id = str_replace(' ', '', $value);
@$trimChar = substr($value, 1);
$url_prefix = $domain_with_http . "/database/Map.php";
if (!empty($value) OR $value == "NULL" OR $value == "0") {
  if ($key == "latitude") @$url_suffix = @$url_suffix . "?" . $key . "=" . $value;
  if ($key != "latitude") @$url_suffix = @$url_suffix . "&" . $key . "=" . $value;

  include "DB-filter.php";
 }
}
// if ($_POST['value'] == "Search on Map") redir("$url","0");
if (!empty($db_vars)) $db_vars = substr(strstr($db_vars," "), 1);
if (!empty($db_vars)) $db_vars = "WHERE " . substr(strstr($db_vars," "), 1);
@$db_vars = str_replace('WHERE AND', 'WHERE', @$db_vars);
 ?>
