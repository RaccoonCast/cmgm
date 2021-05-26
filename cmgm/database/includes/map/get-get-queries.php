<?php
$db_variables = "id > 0";
$url_suffix = null;

foreach($_GET as $key => $value){

  if ($key == "latitude" OR $key == "longitude" OR $key == "zoom" OR $key == "limit" OR $key == "marker_latitude" OR $key == "marker_longitude") {
      ${$key} = $value;
    }
    elseif ($key == "tags") {
    $url_suffix = $url_suffix . "&" . $key . "=" . $value;
    $db_variables = $key . ' like "%'.$value.'%" AND ' . $db_variables;
    } elseif(!empty($value)) {

    if ($key != "mp-id") {
    $url_suffix = $url_suffix . "&" . $key . "=" . $value;
    }
    if ($key == "back") {
    ${$key} = $value;
    }

    $trimChar = substr($value, 1);
    if ($value[0] == "!"){
    $db_variables = "NOT " . $key . ' = "'.$trimChar.'" AND ' . $db_variables;
    } elseif ($value[0] == ">") {
    $db_variables = $key . ' > '.$trimChar.' AND ' . $db_variables;
    } elseif ($value[0] == "<") {
    $db_variables = $key . ' < '.$trimChar.' AND ' . $db_variables;
    } elseif ($key != "back") {
      $db_variables = $key . ' = "'.$value.'" AND ' . $db_variables;
    }

  }
}
?>
