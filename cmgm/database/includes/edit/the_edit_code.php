<?php
// Edit
$sql_edit = "UPDATE database_db SET ";

// Add all the edited fields to the $sql_edit query.
if (isset($_POST['id'])) foreach ($list_of_vars as $value) {
        if (strpos($value, 'street_view_url') === false OR empty($_POST[$value])) {
          if ($_POST[$value] != ${$value}) $sql_edit = $sql_edit . "$value = '".mysqli_real_escape_string($conn, $_POST[$value])."', ";
          } else {
            if ("https://" . $_POST[$value] != ${$value}) $sql_edit = $sql_edit . "$value = '".mysqli_real_escape_string($conn, "https://" . str_replace("https://", "",$_POST[$value]))."', ";
          }
          ${$value} = $_POST[$value];
          if (strpos($value, 'street_view_url') !== false) if (!empty($_POST[$value])) ${$value} = "https://" . str_replace("https://", "",$_POST[$value]);
    }

// Remove last comma from the query.
if (isset($id)) $sql_edit = rtrim($sql_edit,', ') . " WHERE id = $id";

if (strlen($sql_edit) != 37) mysqli_query($conn, $sql_edit);
?>
