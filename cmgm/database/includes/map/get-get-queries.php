<?php
$db_vars = "id > 0";
$url_suffix = null;

foreach($_GET as $key => $value){

  if ($key == "latitude" OR $key == "longitude" OR $key == "zoom" OR $key == "limit" OR $key == "marker_latitude" OR $key == "marker_longitude") {
      ${$key} = $value;
    }
    elseif ($key == "tags") {
    $url_suffix = $url_suffix . "&" . $key . "=" . $value;
    $db_vars = $key . ' like "%'.$value.'%" AND ' . $db_vars;
    } elseif ($key == "date") {
    $trimChar = substr($value, 1);
    $url_suffix = $url_suffix . "&" . $key . "=" . $value;
    if ($value[0] == ">") $db_vars = "date_added" . ' >= "'.$trimChar.'" AND ' . $db_vars;
    elseif ($value[0] == "<") $db_vars = "date_added" . ' <= "'.$trimChar.'" AND ' . $db_vars;
    } elseif(!empty($value)) {

    if ($key != "mp-id") {
    $url_suffix = $url_suffix . "&" . $key . "=" . $value;
    }
    if ($key == "back") {
    ${$key} = $value;
    }

    $trimChar = substr($value, 1);
    if ($value[0] == "!"){
    $db_vars = "NOT " . $key . ' = "'.$trimChar.'" AND ' . $db_vars;
    } elseif ($value[0] == ">") {
    $db_vars = $key . ' > '.$trimChar.' AND ' . $db_vars;
    } elseif ($value[0] == "<") {
    $db_vars = $key . ' < '.$trimChar.' AND ' . $db_vars;
    } elseif ($key != "back") {
      $db_vars = $key . ' = "'.$value.'" AND ' . $db_vars;
    }

  }
}
?>
