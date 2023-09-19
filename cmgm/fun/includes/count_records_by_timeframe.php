<?php
     $today = date("Y-m-d");
     $yesterday = date("Y-m-d", strtotime("-1 day"));
     $last_7_days = date("Y-m-d", strtotime("-1 week"));
     $last_30_days = date("Y-m-d", strtotime("-1 month"));
     $last_90_days = date("Y-m-d", strtotime("-3 month"));
     $all_time = "2000-01-01";

     $sql = "SELECT
             SUM(CASE WHEN date_added = ? THEN 1 ELSE 0 END) AS record_count_today,
             SUM(CASE WHEN date_added = ? THEN 1 ELSE 0 END) AS record_count_yesterday,
             SUM(CASE WHEN date_added >= ? THEN 1 ELSE 0 END) AS record_count_last_7_days,
             SUM(CASE WHEN date_added >= ? THEN 1 ELSE 0 END) AS record_count_last_30_days,
             SUM(CASE WHEN date_added >= ? THEN 1 ELSE 0 END) AS record_count_last_90_days,
             SUM(CASE WHEN date_added >= ? THEN 1 ELSE 0 END) AS record_count_all_time_days
             FROM db WHERE 1=1 $db_vars_unamended";

     $stmt = $conn->prepare($sql);
     $stmt->bind_param("ssssss", $today, $yesterday, $last_7_days, $last_30_days, $last_90_days, $all_time);
     $stmt->execute();

     $result = $stmt->get_result();
     $row = $result->fetch_assoc();

     $url = $domain_with_http . removeParameterFromURL($current_url, "date");

     $today_records = ($row["record_count_today"] > 0) ? '<a href="' . $url . "&date=" . $today . '">'. $row["record_count_today"] .'</a>' : '0';
     $yesterday_records = ($row["record_count_yesterday"] > 0) ? '<a href="' . $url . "&date=>" . $yesterday . '">'. $row["record_count_yesterday"] .'</a>' : '0';
     $last_7_days_records = ($row["record_count_last_7_days"] > 0) ? '<a href="' . $url . "&date=>" . $last_7_days . '">'. $row["record_count_last_7_days"] .'</a>' : '0';
     $last_30_days_records = ($row["record_count_last_30_days"] > 0) ? '<a href="' . $url . "&date=>" . $last_30_days . '">'. $row["record_count_last_30_days"] .'</a>' : '0';
     $last_90_days_records = ($row["record_count_last_90_days"] > 0) ? '<a href="' . $url . "&date=>" . $last_90_days . '">'. $row["record_count_last_90_days"] .'</a>' : '0';
     $all_time_records = ($row["record_count_all_time_days"] > 0) ? '<a href="' . $url . "&date=>" . $all_time . '">'. $row["record_count_all_time_days"] .'</a>' : '0';

     echo "Created today: " . $today_records . PHP_EOL;
     echo "<br> Created yesterday: " . $yesterday_records . PHP_EOL;
     echo "<br> Created within last 7 days: " . $last_7_days_records . PHP_EOL;
     echo "<br> Created within last 30 days: " . $last_30_days_records . PHP_EOL;
     echo "<br> Created within last 90 days: " . $last_90_days_records . PHP_EOL;
     echo "<br> Created all time: " . $all_time_records . PHP_EOL . "<br>";

     $stmt->close();

     echo "<br>";
?>
