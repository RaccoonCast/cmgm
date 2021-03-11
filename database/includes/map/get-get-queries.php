<?php
$db_variables = "id > 0";
$url_suffix = null;

foreach($_GET as $key => $value){

  if ($key == "latitude" OR $key == "longitude" OR $key == "address" OR $key == "zip" OR $key == "city" OR $key == "state" OR $key == "zoom" OR $key == "limit") {
    ${$key} = $value;
  } elseif(!empty($value)) {


    $trimChar = substr($value, 1);
    if ($value[0] == "!"){
    $db_variables = "NOT " . $key . ' = "'.$trimChar.'" AND ' . $db_variables;
    } elseif ($value[0] == ">") {
    $db_variables = $key . ' > '.$trimChar.' AND ' . $db_variables;
    } elseif ($value[0] == "<") {
    $db_variables = $key . ' < '.$trimChar.' AND ' . $db_variables;
    } else {
      $db_variables = $key . ' = "'.$value.'" AND ' . $db_variables;
      $url_suffix = $url_suffix . "&" . $key . "=" . $value;
    }

  }
}
?>
