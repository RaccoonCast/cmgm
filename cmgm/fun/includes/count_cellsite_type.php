<?php
$sql = "SELECT cellsite_type, COUNT(cellsite_type) AS count FROM db WHERE $db_vars_unamended AND cellsite_type <> '' GROUP BY cellsite_type ORDER BY count DESC LIMIT $limit;";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
  $url = '<a href="' . $current_url . '&cellsite_type='.$row["cellsite_type"].'">'. $row["count"].'</a>';

  include "../includes/functions/tower_types.php";
  $category = ucfirst(explode('_', $row['cellsite_type'])[0]);
  $cellsite_type = $options[$category][$row['cellsite_type']];
  $cellsite_type_normalized = $cellsite_type . @$category_suffix;
  
  echo $cellsite_type_normalized . ": " . $url . "<br>";
}

echo "<br>";
?>
