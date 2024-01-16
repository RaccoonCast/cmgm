<?php
$today = date("Y-m-d");
$yesterday = date("Y-m-d", strtotime("-1 day"));
$last_7_days = date("Y-m-d", strtotime("-1 week"));
$last_30_days = date("Y-m-d", strtotime("-1 month"));
$last_90_days = date("Y-m-d", strtotime("-3 months"));
$all_time = "2000-01-01";

$sql = "SELECT
         SUM(CASE WHEN date_added LIKE '$today%' THEN 1 ELSE 0 END) AS record_count_today,
         SUM(CASE WHEN date_added LIKE '$yesterday%' THEN 1 ELSE 0 END) AS record_count_yesterday,
         SUM(CASE WHEN date_added >= '$last_7_days' THEN 1 ELSE 0 END) AS record_count_last_7_days,
         SUM(CASE WHEN date_added >= '$last_30_days' THEN 1 ELSE 0 END) AS record_count_last_30_days,
         SUM(CASE WHEN date_added >= '$last_90_days' THEN 1 ELSE 0 END) AS record_count_last_90_days,
         SUM(CASE WHEN date_added >= '$all_time' THEN 1 ELSE 0 END) AS record_count_all_time_days
         FROM db WHERE $db_vars_unamended";

$result = $conn->query($sql);

// Access the counts if the query was successful
if ($result) {
    $row = $result->fetch_assoc();

    $today_records = ($row["record_count_today"] > 0) ? '<a href="' . $current_url . "&date=" . $today . '">'. $row["record_count_today"] .'</a>' : '0';
    $yesterday_records = ($row["record_count_yesterday"] > 0) ? '<a href="' . $current_url . "&date=" . $yesterday . '">'. $row["record_count_yesterday"] .'</a>' : '0';
    $last_7_days_records = ($row["record_count_last_7_days"] > 0) ? '<a href="' . $current_url . "&date=>" . $last_7_days . '">'. $row["record_count_last_7_days"] .'</a>' : '0';
    $last_30_days_records = ($row["record_count_last_30_days"] > 0) ? '<a href="' . $current_url . "&date=>" . $last_30_days . '">'. $row["record_count_last_30_days"] .'</a>' : '0';
    $last_90_days_records = ($row["record_count_last_90_days"] > 0) ? '<a href="' . $current_url . "&date=>" . $last_90_days . '">'. $row["record_count_last_90_days"] .'</a>' : '0';
    $all_time_records = ($row["record_count_all_time_days"] > 0) ? '<a href="' . $current_url . "&date=>" . $all_time . '">'. $row["record_count_all_time_days"] .'</a>' : '0';

    echo "Created today: " . $today_records . PHP_EOL;
    echo "<br> Created yesterday: " . $yesterday_records . PHP_EOL;
    echo "<br> Created within last 7 days: " . $last_7_days_records . PHP_EOL;
    echo "<br> Created within last 30 days: " . $last_30_days_records . PHP_EOL;
    echo "<br> Created within last 90 days: " . $last_90_days_records . PHP_EOL;
    echo "<br> Created all time: " . $all_time_records . PHP_EOL . "<br>";

    $result->free_result();
}

     echo "<br>";
?>
