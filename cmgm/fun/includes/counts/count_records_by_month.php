<?php
$sql = "SELECT MONTH(date_added) AS month, COUNT(*) AS count FROM db WHERE $db_vars GROUP BY month ORDER BY month;";
$result = $conn->query($sql);

$monthNames = array_combine(range(1, 12), ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]);

$year_tmp = isset($_GET['year']) ? "during {$_GET['year']}" : "during every year";
if (!isset($json_flag)) echo "<h3>Pins created during $year_tmp</h3>";

while ($row = $result->fetch_assoc()) {

   if (isset($_GET['percents_view'])) {
    echo $monthNames[$row["month"]] . ': ' . '<a href="'.$current_url . '&month='. $row["month"].'">'.getPercent($row["count"]).'</a><br>';
    } elseif (isset($json_flag)) {
      $json_array[$monthNames[$row["month"]]] = $row["count"];
    } else {
      echo $monthNames[$row["month"]] . ': ' . '<a href="'.$current_url . '&month='. $row["month"].'">'.$row["count"].'</a><br>';
    }


}

echo (isset($_GET['json_flag'])) ? "<br>" : "";
?>
