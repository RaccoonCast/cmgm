<?php
foreach($_GET as $key => $value){
  if ($key == "latitude" OR $key == "longitude" OR $key == "limit") {
    ${$key} = $value;
  } elseif ($key == "id") {
    if (!empty($value)) {
    ${$key} = $value;
    $id = str_replace(' ', '', $id);
    $db_vars = "AND (id = '$id' OR LTE_1 = '$id' OR LTE_2 = '$id' OR LTE_3 = '$id' OR LTE_4 = '$id' OR LTE_5 = '$id' OR LTE_6 = '$id' OR NR_1 = '$id' OR NR_2 = '$id') " . $db_vars;
  }
} elseif ($key == "fileSearch") {
    if (!empty($value)) {
    ${$key} = $value;
    $fs = str_replace(' ', '', $fileSearch);
    $db_vars = "AND evidence_a='$fs' OR evidence_b='$fs' OR evidence_c='$fs' OR photo_a='$fs' OR photo_b='$fs' OR photo_c='$fs' OR photo_d='$fs' OR photo_e='$fs' OR photo_f='$fs' OR extra_a='$fs' OR extra_b='$fs' OR extra_c='$fs'" . $db_vars;
  }
} elseif ($key == "street_view") {
    ${$key} = $value;
    if (${$key} == "true") {
      $db_vars = "AND street_view_url_a != '' "  . $db_vars;
    } elseif (${$key} == "false") {
      $db_vars = "AND street_view_url_a = '' "  . $db_vars;
    }
} elseif ($key == "address" && !empty($value)) {
    ${$key} = $value;
    $db_vars = "AND " . $key . ' like "%'.$value.'%"' . $db_vars;
  } else {
    if (!empty($value)) {
      @$db_vars = "AND " . $key . ' = "'.$value.'" ' . $db_vars;
    }
  }
}
if (!empty($db_vars)) $db_vars = "WHERE " . substr(strstr($db_vars," "), 1);
?>
