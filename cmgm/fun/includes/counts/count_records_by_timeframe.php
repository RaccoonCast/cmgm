<?php
// Define date ranges
$today = date("Y-m-d");
$yesterday = date("Y-m-d", strtotime("-1 day"));
$within_last_7_days = date("Y-m-d", strtotime("-1 week"));
$within_last_30_days = date("Y-m-d", strtotime("-1 month"));
$within_last_90_days = date("Y-m-d", strtotime("-3 months"));
$all_time = "2000-01-01";

// Define SQL query
$sql = "SELECT
    SUM(CASE WHEN date_added LIKE '$today%' THEN 1 ELSE 0 END) AS record_count_today,
    SUM(CASE WHEN date_added LIKE '$yesterday%' THEN 1 ELSE 0 END) AS record_count_yesterday,
    SUM(CASE WHEN date_added >= '$within_last_7_days' THEN 1 ELSE 0 END) AS record_count_within_last_7_days,
    SUM(CASE WHEN date_added >= '$within_last_30_days' THEN 1 ELSE 0 END) AS record_count_within_last_30_days,
    SUM(CASE WHEN date_added >= '$within_last_90_days' THEN 1 ELSE 0 END) AS record_count_within_last_90_days,
    SUM(CASE WHEN date_added >= '$all_time' THEN 1 ELSE 0 END) AS record_count_all_time
    FROM db WHERE $db_vars_unamended";

// Execute the query
$result = $conn->query($sql);

// Access the counts if the query was successful
if ($result) {
    $row = $result->fetch_assoc();

    // Display results
    $periods = [ 'today', 'yesterday', 'within_last_7_days', 'within_last_30_days', 'within_last_90_days', 'all_time' ];

    foreach ($periods as $period) {
        $count = $row["record_count_$period"];
        $link = "<a href='{$current_url}&date=>{$$period}'>$count</a>";



        echo "Created " . str_replace('_', ' ', $period) . ": " . ($count > 0 ? $link : "0") . "<br>";
    }

    // Free the result set
    $result->free_result();
}

echo "<br>";
?>
