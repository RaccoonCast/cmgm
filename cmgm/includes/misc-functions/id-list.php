<?php
include "../../database/includes/DB-filter-get.php";
include '../functions/sqlpw.php';

$sql = "SELECT DISTINCT LTE_1,LTE_2,LTE_3,LTE_4,LTE_5,LTE_6,NR_1,NR_2, (3959 * ACOS(COS(RADIANS($latitude)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS($longitude)) + SIN(RADIANS($latitude)) * SIN(RADIANS(latitude)))) AS DISTANCE FROM db ".@$db_vars." ORDER BY distance";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) foreach ($row as $field => $value) if (strlen($value) <= 7 && !empty($value)) echo $value , PHP_EOL
?>
