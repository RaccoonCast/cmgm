<?php
  $sql = "SELECT YEAR(date_added) AS creation_year, COUNT(*) AS record_count FROM db WHERE $db_vars GROUP BY YEAR(date_added) ORDER BY creation_year";
  $result = $conn->query($sql);

  while ($row = $result->fetch_assoc()) {

    if (isset($_GET['percents_view'])) {
      $url_for_year = '<a href="' . $domain_with_http . $current_url . "&year=" . $row["creation_year"] . '">' . getPercent($row['record_count']) . '</a>';
    } elseif (isset($json_flag)) {
      $json_array[$row["creation_year"]] = $row['record_count'];
    } else {
      $url_for_year = '<a href="' . $domain_with_http . $current_url . "&year=" . $row["creation_year"] . '">' . $row['record_count'] . '</a>';
    }

    echo (!isset($json_flag)) ? "" . $row["creation_year"] . ": " . $url_for_year . '<br>' . PHP_EOL : "";

  }

  echo (!isset($json_flag)) ? "<br>" : "";
?>
