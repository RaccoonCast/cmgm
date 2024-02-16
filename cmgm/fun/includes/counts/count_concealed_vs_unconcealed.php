<?php
// SQL query
$sql = 'SELECT
            SUM(CASE WHEN concealed = "true" THEN 1 ELSE 0 END) AS concealed_true_count,
            SUM(CASE WHEN concealed = "false" THEN 1 ELSE 0 END) AS concealed_false_count
        FROM db WHERE '.$db_vars.' ';

$result = $conn->query($sql);

// Output data
while ($row = $result->fetch_assoc()) {

    if (isset($_GET['percents_view'])) {
      echo "Unconcealed sites: " .  '<a href="' . $current_url . '&concealed=false">' . getPercent($row["concealed_false_count"])  . '</a><br>';
      echo "Concealed sites: " .  '<a href="' . $current_url . '&concealed=true">' . getPercent($row["concealed_true_count"])  . '</a><br>';
      } elseif (isset($json_flag)) {
        $json_array["unconcealed"] = $row["concealed_false_count"];
        $json_array["concealed"] = $row["concealed_true_count"];
      } else {
        echo "Unconcealed sites: " .  '<a href="' . $current_url . '&concealed=false">' . $row["concealed_false_count"]  . '</a><br>';
        echo "Concealed sites: " .  '<a href="' . $current_url . '&concealed=true">' . $row["concealed_true_count"]  . '</a><br>';
      }


}
echo (isset($_GET['json_flag'])) ? "<br>" : "";
?>
