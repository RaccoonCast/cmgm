<?php
    $allowGuests = "true";
    include "../functions.php";
    // Most common carriers are
    $carriers = ["T-Mobile", "Sprint", "Verizon", "ATT"];
    $carrierCounts = [];
    $username_suffix = isset($_GET['username']) ? 'AND created_by = "'.$_GET['username'].'"' : '';

      foreach ($carriers as $carrier) {
        $sql = "SELECT COUNT(*) as count FROM db WHERE carrier = '$carrier' $username_suffix";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $carrierCounts[$carrier] = $row['count'];
        $result->close();
      }
      foreach ($carrierCounts as $carrier => $count) {
        echo "$carrier: $count<br>";
      }

      echo "<br>"; // Records created during X year

      $sql = "SELECT YEAR(date_added) AS creation_year, COUNT(*) AS record_count FROM db WHERE 1=1 $username_suffix GROUP BY YEAR(date_added) ORDER BY creation_year";

      $result = $conn->query($sql);

      while ($row = $result->fetch_assoc()) {
        echo "" . $row["creation_year"] . ": " . $row["record_count"] . '<br>' . PHP_EOL;
      }

      echo "<br>"; // Records created within last x

      $today = date("Y-m-d");
      $yesterday = date("Y-m-d", strtotime("-1 day"));
      $last_7_days = date("Y-m-d", strtotime("-1 week"));
      $last_30_days = date("Y-m-d", strtotime("-1 month"));

      $sql = "SELECT
              SUM(CASE WHEN date_added = ? THEN 1 ELSE 0 END) AS record_count_today,
              SUM(CASE WHEN date_added = ? THEN 1 ELSE 0 END) AS record_count_yesterday,
              SUM(CASE WHEN date_added >= ? THEN 1 ELSE 0 END) AS record_count_last_7_days,
              SUM(CASE WHEN date_added >= ? THEN 1 ELSE 0 END) AS record_count_last_30_days
              FROM db WHERE 1=1 $username_suffix";

      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ssss", $today, $yesterday, $last_7_days, $last_30_days);
      $stmt->execute();

      $result = $stmt->get_result();
      $row = $result->fetch_assoc();

      echo "Created today: " . $row["record_count_today"] . PHP_EOL;
      echo "<br> Created yesterday: " . $row["record_count_yesterday"] . PHP_EOL;
      echo "<br> Created within last 7 days: " . $row["record_count_last_7_days"] . PHP_EOL;
      echo "<br> Created within last 30 days: " . $row["record_count_last_30_days"] . PHP_EOL;

      $stmt->close();

      echo "<br><br>"; // Records created during each month of the year

      $sql = "SELECT YEAR(date_added) AS year, MONTH(date_added) AS month, COUNT(*) AS record_count FROM db WHERE YEAR(date_added) = YEAR(CURRENT_DATE) $username_suffix GROUP BY year, month ORDER BY year, month";
      $result = $conn->query($sql);

      // Check if the query was successful
      if ($result) {
        // Fetch and display the results
        while ($row = $result->fetch_assoc()) {
          $monthNames = ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

          echo $monthNames[$row["month"]] . ": " . $row["record_count"] . "<br>";
        }
      } else {
        echo "Error: " . $conn->error;
      }

      echo "<br>"; // Most records added during X day.

      $sql = "SELECT date_added, COUNT(date_added) AS value_occurrence FROM db WHERE 1=1 $username_suffix GROUP BY date_added ORDER BY value_occurrence DESC LIMIT 10 OFFSET 1";
      $result = $conn->query($sql);

      while ($row = $result->fetch_assoc()) {
        echo $row["value_occurrence"] . " records created on " . $row["date_added"] . "<br>";
      }

      echo "<br>"; // Most records added during X day.

      $sql = "SELECT city,state, COUNT(city) AS city_count FROM db WHERE 1=1 $username_suffix GROUP BY city ORDER BY city_count DESC LIMIT 15;";
      $result = $conn->query($sql);

      while ($row = $result->fetch_assoc()) {
        echo $row["city_count"] . " records created in " . $row["city"] . ', ' . $row['state'] . "<br>";
      }

      $conn->close();
 ?>
