<?php
$db_variables = "id > 0";

foreach($_GET as $key => $value){
  if ($key == "latitude" OR $key == "longitude" OR $key == "address" OR $key == "zip" OR $key == "city" OR $key == "state" OR $key == "zoom" OR $key == "limit") {
    ${$key} = $value;
  } else {
    $trimExclamationMark = substr($value, 1);
    if ($value[0] == "!"){
    $db_variables = "NOT " . $key . ' = "'.$trimExclamationMark.'" AND ' . $db_variables;
    } else {
    $db_variables = $key . ' = "'.$value.'" AND ' . $db_variables;
    }
  }
}
?>
