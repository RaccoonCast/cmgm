<?php
$sql = "SELECT cellsite_type, COUNT(cellsite_type) AS count FROM db WHERE $db_vars AND cellsite_type <> '' GROUP BY cellsite_type ORDER BY count DESC LIMIT $limit;";
$result = $conn->query($sql);

if (!isset($json_flag)) echo "<h3>Cellsite types</h3>";

while ($row = $result->fetch_assoc()) {

  include SITE_ROOT . "/includes/functions/tower_types.php";
  $category = ucfirst(explode('_', $row['cellsite_type'])[0]);
  $cellsite_type = $options[$category][$row['cellsite_type']];
  $cellsite_type_normalized = $cellsite_type . @$category_suffix;
  
  if (isset($_GET['percents_view'])) {
    $url = '<a href="' . $current_url . '&cellsite_type='.$row["cellsite_type"].'">'. getPercent($row["count"]).'</a>';
    echo $cellsite_type_normalized . ": " . $url . "<br>";
  } elseif (isset($json_flag)) {
    $json_array[$cellsite_type_normalized] = $row["count"];
  } else {
    $url = '<a href="' . $current_url . '&cellsite_type='.$row["cellsite_type"].'">'. $row["count"].'</a>';
    echo $cellsite_type_normalized . ": " . $url . "<br>";
  }

}

echo (isset($_GET['json_flag'])) ? "<br>" : "";
?>
