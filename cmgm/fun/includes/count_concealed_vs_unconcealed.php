<?php
// SQL query
$sql = 'SELECT
            SUM(CASE WHEN concealed = "true" THEN 1 ELSE 0 END) AS concealed_true_count,
            SUM(CASE WHEN concealed = "false" THEN 1 ELSE 0 END) AS concealed_false_count
        FROM db WHERE '.$db_vars_unamended.' ';

$result = $conn->query($sql);

// Output data
while ($row = $result->fetch_assoc()) {
    echo "Concealed sites: " . $row["concealed_true_count"] . "<br>";
    echo "Unconcealed sites: " . $row["concealed_false_count"] . "<br>";
}
echo '<br>';
?>
