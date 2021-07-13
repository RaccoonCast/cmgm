<?php
$sql_read = "SELECT ";
foreach ($list_of_vars as $value) $sql_read = $sql_read . $value . ",";
$sql_read = rtrim($sql_read, ',') . " FROM database_db WHERE id = $id;";
$sql_read_result = mysqli_query($conn,$sql_read);
if (!empty($id)) if (is_numeric($id)) while($row = $sql_read_result->fetch_assoc()) foreach ($row as $key => $value) $$key = $value;
?>
