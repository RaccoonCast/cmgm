<?php
$sql_read = "SELECT * FROM db WHERE id = $id";
if (!empty($id)) if (is_numeric($id)) $sql_read_result = mysqli_query($conn,$sql_read);
if (!empty($id)) if (is_numeric($id)) while($row = $sql_read_result->fetch_assoc()) foreach ($row as $key => $value) $$key = $value;
?>
