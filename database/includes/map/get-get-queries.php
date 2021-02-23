<?php
$db_variables = "id > 0";

foreach($_GET as $key => $value){
  if ($key == "latitude" OR $key == "longitude" OR $key == "address" OR $key == "zip" OR $key == "city" OR $key == "state") {
    ${$key} = $value;
  } else {
    $db_variables = $key . ' = "'.$value.'" AND ' . $db_variables;
  }
}
?>
