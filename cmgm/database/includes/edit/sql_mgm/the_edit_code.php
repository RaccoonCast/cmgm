<?php
// Edit
$sql_edit = "UPDATE db SET ";

if (isset($_POST['edittag'])) { foreach ($_POST as $key => $value) {
    if ($key != "username") {
        $value = strip_tags($value);

    } 
    
    $fieldsToReplace = ['evidence_a', 'evidence_b', 'evidence_c', 'extra_a', 'extra_b', 'extra_c', 'extra_d', 'extra_e', 'extra_f', 'photo_a', 'photo_b', 'photo_c', 'photo_c', 'photo_d', 'photo_e', 'photo_f'];
    $removeLeadindZeroes = ['sv_a_date', 'sv_b_date', 'sv_c_date', 'sv_d_date', 'sv_e_date', 'sv_f_date'];
    if (in_array($key, $fieldsToReplace) && substr($value, 0, 1) === '$') {
        $value = 'https://siteportal.calepa.ca.gov/nsite/map/results/summary/' . substr($value, 1);
    } 
    if (in_array($key, $fieldsToReplace) && substr($value, 0, 1) === '!') {
        $value = 'https://web.archive.org/web/' . substr($value, 1);
    } 
    if (in_array($key, $fieldsToReplace) && substr($value, 0, 22) === 'https://canon.cmgm.us/') {
        $value = '@' . str_replace("%5C", "/", substr($value, 22));
    } 
    if (in_array($key, $fieldsToReplace) && substr($value, 0, 1) === '@' && is_numeric(substr($value, 1, 2))) {
      $value = file_get_contents('https://canon.cmgm.us/getPath.php?q='.substr($value, 1).'&doNotRedir');
    } 
    if (in_array($key, $removeLeadindZeroes) && substr($value, 0, 1) === '0') {
        $value = substr($value, 1);
    } 

    $value = preg_match('/unique_system_identifier=(\d+)/', $value, $matches) ? "https://wireless2.fcc.gov/UlsApp/UlsSearch/licenseLocSum.jsp?licKey={$matches[1]}" : $value;
    $value = preg_match('/https:\/\/maprad\.io\/us\/search\/licence\/.*?\/.*?-(\d+)/', $value, $matches) ? "https://wireless2.fcc.gov/UlsApp/UlsSearch/licenseLocSum.jsp?licKey={$matches[1]}" : $value;

    include "latitude_longitude.php"; 
    if (@${@$key} != $value && $key != "evidence_score" && $key != "edittag" && $key != "latitude" && $key != "longitude" && $key != "edit_history" && @$key != "edit_lock" && @$key != "id" && @$key != "new" && @$key != "date_added" && $key != "multiplier") {
        if (strpos($key, 'sv') === false) { $sql_edit .= "$key = '" . mysqli_real_escape_string($conn, $value) . "', ";}
        if (strpos($key, 'sv') !== false) { $sql_edit .= "$key = '" . mysqli_real_escape_string($conn, str_replace("https://", "", $value)) . "', ";}
        include "history.php";
    } 
        ${$value} = @$_POST[$value];
    }
}


if (strlen($sql_edit) != 14) {
  $sql_edit .= "edit_date = '" . date("Y-m-d H") . "', ";
  $sql_edit .= "edit_userid = '" . $userID . "', ";
  $sql_edit .= "edit_username = '" . $username . "', ";

  // echo "Former: " . $edit_date . "<br>";
  // echo "Current: " . date("Y-m-d H") . "<br>";
  // echo "Former: " . $edit_userid . "<br>";
  // echo "Current: " . $userID . "<br>";
  if (@$pci_removed_note == "true") @$vals .= "PCIs removed." . PHP_EOL;
  if (@$pci_updated_note == "true") @$vals .= "PCIs updated." . PHP_EOL;

  if (isset($_POST['new'])) {
    $edit_history_value = "$edit_history" . "————— " . date("Y-m-d H:i") . " | $username created —————" . PHP_EOL . "$vals";
    $sql_edit .= " edit_history = '".mysqli_real_escape_string($conn, $edit_history_value)."' WHERE id = $id";
  }

  if (!isset($_POST['new']))  {
  if ($edit_date != date("Y-m-d H") OR $edit_userid != $userID) {
    $edit_history_value = "$edit_history" . "————————— " . date("Y-m-d H:i") . " | $username —————————" . PHP_EOL . "$vals";
    $sql_edit .= " edit_history = '".mysqli_real_escape_string($conn, $edit_history_value)."' WHERE id = $id";
  } else {
    $edit_history_value = "$edit_history" . "$vals";
    $sql_edit .= " edit_history = '".mysqli_real_escape_string($conn, $edit_history_value)."' WHERE id = $id";
  }
  }
  // echo $sql_edit;
  //die();

  if ((is_numeric($_POST['latitude']) && is_numeric($_POST['longitude']) OR (is_numeric(@$tmp_latitude) && is_numeric(@$tmp_longitude)))) mysqli_query($conn, $sql_edit);
  include "read_data.php";
} else {
}

?>
