<?php
// this was a fun idea, cba.
  if (strlen(@$_POST['IMPORT_PCI'])>200) {
    $cellmapper_data = $_POST['IMPORT_PCI'];
    $cellmapper_data = substr($cellmapper_data, strpos($cellmapper_data, "Cell ") + 5);
    $string_2 = explode("\n", $cellmapper_data);

    foreach ($string_2 as &$value) {
      if (substr($value, 0, 3) == "PCI") $val = @$val .$value;
    }
    echo $val;
    die();
    @$val = preg_replace("/\([^)]+\)/","",@$val);
    @$val = trim(preg_replace('/\s\s+/', ', ', @$val));

    @$val = str_replace("PCI	","",@$val);
    @$val = "ASDOJASIDJAIOdJ, " . @$val;
    @$val = implode(',',array_unique(explode(',', @$val)));
    @$val = rtrim($val, ",");
    @$val = ltrim($val, "ASDOJASIDJAIOdJ, ");
    @$arr = explode(', ', @$val);

    $sql_edit .= "PCI_1 = '".mysqli_real_escape_string($conn, $arr[0] ?? null)."', ";
    $sql_edit .= "PCI_2 = '".mysqli_real_escape_string($conn, $arr[1] ?? null)."', ";
    $sql_edit .= "PCI_3 = '".mysqli_real_escape_string($conn, $arr[2] ?? null)."', ";
    $sql_edit .= "PCI_4 = '".mysqli_real_escape_string($conn, $arr[3] ?? null)."', ";
    $sql_edit .= "PCI_5 = '".mysqli_real_escape_string($conn, $arr[4] ?? null)."', ";
    $sql_edit .= "PCI_6 = '".mysqli_real_escape_string($conn, $arr[5] ?? null)."', ";
    $sql_edit .= "PCI_7 = '".mysqli_real_escape_string($conn, $arr[6] ?? null)."', ";
    $sql_edit .= "PCI_8 = '".mysqli_real_escape_string($conn, $arr[7] ?? null)."', ";
    $sql_edit .= "PCI_9 = '".mysqli_real_escape_string($conn, $arr[8] ?? null)."', ";
    $sql_edit .= "PCI_10 = '".mysqli_real_escape_string($conn, $arr[9] ?? null)."', ";
    $sql_edit .= "PCI_11 = '".mysqli_real_escape_string($conn, $arr[10] ?? null)."', ";
    $sql_edit .= "PCI_12 = '".mysqli_real_escape_string($conn, $arr[11] ?? null)."', ";
    $sql_edit .= "PCI_13 = '".mysqli_real_escape_string($conn, $arr[12] ?? null)."', ";
    $sql_edit .= "PCI_14 = '".mysqli_real_escape_string($conn, $arr[13] ?? null)."', ";
  }
